<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\FileImageResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class FileController extends Controller
{
    private const IMAGE_RESOURCES_SIZES = [
        1920,
        1024,
        768,
        540,
        300,
        100
    ];

    private const RESOURCES_PATH = 'files/resources/';

    public function list(File $folder) {
        $folder->load('childs.resources');

        return response()->json($folder->toArray()['childs']);
    }

    public function upload(Request $request) {
        $files          = [];
        $parentFolderID = $request->get('folder');
        $parentFolder   = File::with('childs')->findOrFail($parentFolderID);

        $foldersNames = [];

        foreach ($parentFolder->childs as $folder) {
            $foldersNames[] = $folder->name;
        }

        $postFiles = $request->file('files');

        foreach ($postFiles as $file) {
            $uuid = self::genUuid();

            $mime = $file->getMimeType();

            $file->storeAs('files/', $uuid, 'local');

            $furl = NULL;

            $ext       = '.' . strtolower($file->getClientOriginalExtension());
            $fnamePart = substr($file->getClientOriginalName(), 0, -strlen($ext));

            $count = -1;
            $name  = NULL;

            do {
                if (++$count > 0) {
                    $name = "$fnamePart ($count)$ext";
                } else {
                    $name = $fnamePart . $ext;
                }
            } while (in_array($name, $foldersNames, true));

            $fileBuffer          = new File();
            $fileBuffer->file_id = $parentFolderID;
            $fileBuffer->uuid    = $uuid;
            $fileBuffer->name    = $name;
            $fileBuffer->mime    = $file->getClientMimeType();
            $fileBuffer->size    = $file->getSize();
            $fileBuffer->folder  = 0;
            $fileBuffer->save();

            if (strpos($mime, 'image/') === 0 && $mime !== 'image/svg+xml') {
                self::createResources($file->get(), $fileBuffer);
            }

            $fileBuffer->load('resources');

            $files[] = $fileBuffer;
        }

        return response()->json($files, 201);
    }

    public static function createResources($byteArray, File $file) {
        $fileImageResources = [];
        $formats            = ['webp'];

        $img = Image::make($byteArray);

        $formats[] = str_replace('image/', NULL, $img->mime());
        $formats   = array_unique($formats, SORT_STRING);

        $localStorage = Storage::disk('local');

        if (!is_dir($localStorage->path(self::RESOURCES_PATH))) {
            $localStorage->makeDirectory(self::RESOURCES_PATH);
        }

        $uuid = null;
        foreach ($formats as $format) {
            foreach (self::generateResources($byteArray, $format, self::IMAGE_RESOURCES_SIZES) as $resourceData) {
                do {
                    $uuid = self::genUuid();
                } while ($localStorage->exists(self::RESOURCES_PATH . $uuid));

                $localStorage->put(self::RESOURCES_PATH . $uuid, $resourceData['resource']);

                $fileImageResources[] = new FileImageResource([
                    'uuid'      => $uuid,
                    'extension' => $format,
                    'width'     => $resourceData['width'],
                ]);
            }
        }

        $file->resources()->saveMany($fileImageResources);
    }

    public function cut(Request $request) {
        $request->validate([
            'folder' => 'required|exists:files,id',
            'items'  => 'required|array',
        ]);

        $target = File::select(['id'])->with('childs')->find($request->get('folder'));

        $itemsColletion = File::whereIn('id', $request->get('items'))->get();

        $fails = [];

        foreach ($target->childs as $child) {
            foreach ($itemsColletion as $k => $item) {
                if ($item->folder === $child->folder && $item->name === $child->name) {
                    $fails[] = $item;

                    $itemsColletion->forget($k);

                    break;
                }
            }
        }

        $ids = [];
        foreach ($itemsColletion as $item) {
            $ids[] = $item->id;
        }

        File::whereIn('id', $ids)->update(['file_id' => $target->id]);

        if ($fails) {
            return response()->json($fails);
        }

        return response(NULL, 204);
    }

    public function copy(Request $request) {
        $request->validate([
            'folder' => 'required|exists:files,id',
            'items'  => 'required|array',
        ]);

        $target = File::select(['id'])->with('childs')->find($request->get('folder'));

        $itemsColletion = File::whereIn('id', $request->get('items'))->get();

        $fails = [];

        foreach ($target->childs as $child) {
            foreach ($itemsColletion as $k => $item) {
                if ($item->folder === $child->folder && $item->name === $child->name) {
                    $fails[] = $item;

                    $itemsColletion->forget($k);

                    break;
                }
            }
        }

        foreach ($itemsColletion as $item) {
            $this->recursiveCopy($item->id, $target->id);
        }

        if ($fails) {
            return response()->json($fails);
        }

        return response(NULL, 204);
    }

    protected function recursiveCopy($sourceID, $targetID): bool {
        $source = File::with(['childs', 'resources'])->findOrFail($sourceID);
        $target = File::select(['id'])->with(['childs'])->find($targetID);

        foreach ($target->childs as $child) {
            if ($child->folder === $source->folder && $child->name === $source->name) {
                return false;
            }
        }

        $localStorage = Storage::disk('local');

        $uuid = null;
        do {
            $uuid = self::genUuid();
        } while ($localStorage->exists("files/$uuid"));

        if (!$source->folder) {
            $localStorage->copy("files/{$source->uuid}", "files/$uuid");
        }

        $newFile          = new File();
        $newFile->file_id = $target->id;
        $newFile->uuid    = $uuid;
        $newFile->name    = $source->name;
        $newFile->mime    = $source->mime;
        $newFile->size    = $source->size;
        $newFile->folder  = $source->folder;
        $newFile->save();

        $fileImageResources = [];

        foreach ($source->resources as $resource) {
            do {
                $uuid = self::genUuid();
            } while ($localStorage->exists(self::RESOURCES_PATH . $uuid));

            $localStorage->copy(self::RESOURCES_PATH . $resource->uuid, self::RESOURCES_PATH . $uuid);

            $fileImageResources[] = new FileImageResource([
                'uuid'      => $uuid,
                'extension' => $resource->extension,
                'width'     => $resource->width,
            ]);
        }

        $newFile->resources()->saveMany($fileImageResources);

        if ($source->folder) {
            foreach ($source->childs as $child) {
                $this->recursiveCopy($child->id, $newFile->id);
            }
        }

        return true;
    }

    public function setName(Request $request, File $file) {
        $request->validate([
            'name' => 'required',
        ]);

        $file->name = $request->get('name');
        $file->save();

        return response(NULL, 204);
    }

    public function setMeta(Request $request, File $file) {
        $file->title = $request->get('title');
        $file->alt   = $request->get('alt');
        $file->save();

        return response(NULL, 204);
    }

    public function makeFolder(Request $request) {
        $request->validate([
            'folder' => 'required|int'
        ]);

        $parentFolderID = $request->get('folder');

        $parentFolder = File::where([
            [
                'id',
                '=',
                $parentFolderID
            ],
            [
                'folder',
                '=',
                1
            ]
        ])
                            ->with('childs')
                            ->firstOrFail();

        $foldersNames = [];

        foreach ($parentFolder->childs as $folder) {
            $foldersNames[] = $folder->name;
        }

        $count = -1;
        $name  = NULL;

        do {
            if (++$count > 0) {
                $name = "Nova pasta ($count)";
            } else {
                $name = 'Nova pasta';
            }

        } while (in_array($name, $foldersNames, true));

        $folder          = new File();
        $folder->file_id = $parentFolderID;
        $folder->uuid    = self::genUuid();
        $folder->name    = $name;
        $folder->folder  = 1;
        $folder->save();

        return response()->json($folder->toArray(), 201);
    }

    public function optimizeImages(Request $request) {
        $images = File::whereIn('id', $request->get('images'))->get();

        foreach ($images as $image) {
            if ($image->optimized) {
                continue;
            }

            $localStorage = Storage::disk('local');

            if (!$localStorage->exists("files/optimized/$image->uuid")) {
                $img = Image::make($localStorage->get("files/{$image->uuid}"));

                $localStorage->put("files/optimized/$image->uuid", (string)$img->encode('webp', 80));
            }

            $image->size      = $localStorage->size("files/optimized/$image->uuid");
            $image->optimized = 1;
            $image->save();
        }

        return response(NULL, 204);
    }

    public function optimizeImagesRevert(Request $request) {
        $images = File::whereIn('id', $request->get('images'))->get();

        foreach ($images as $image) {
            if (!$image->optimized) {
                continue;
            }

            $image->size      = Storage::disk('local')->size("files/$image->uuid");
            $image->optimized = 0;
            $image->save();
        }

        return response(NULL, 204);
    }

    public function delete(Request $request) {
        $request->validate([
            'items' => 'required|array'
        ]);

        File::destroy($request->get('items'));

        return response(NULL, 204);
    }

    protected static function genUuid() {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            // 32 bits for "time_low"
            random_int(0, 0xffff), random_int(0, 0xffff),

            // 16 bits for "time_mid"
            random_int(0, 0xffff),

            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 4
            random_int(0, 0x0fff) | 0x4000,

            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            random_int(0, 0x3fff) | 0x8000,

            // 48 bits for "node"
            random_int(0, 0xffff), random_int(0, 0xffff), random_int(0, 0xffff)
        );
    }

    protected static function generateResource($file, $format, $size) {
        $query = http_build_query([
            'width'  => $size,
            'format' => $format
        ]);

        $ch = curl_init('http://ec2-54-233-101-27.sa-east-1.compute.amazonaws.com/?' . $query);

        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $file,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_RETURNTRANSFER => true,
        ]);

        $output = curl_exec($ch);

        curl_close($ch);

        return $output;
    }

    protected static function generateResources($file, $format, array $sizes) {
        foreach ($sizes as $size) {
            yield [
                'resource' => self::generateResource($file, $format, $size),
                'width'    => $size,
            ];
        }
    }
}
