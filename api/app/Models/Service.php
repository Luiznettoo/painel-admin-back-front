<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Route;

class Service extends Model
{
    use SoftDeletes;




    protected $table = "services";
    protected $with = ['file','banner'];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    protected $fillable = [
        'ordem',
        'title',
        'atendimento',
        'promocao',
        'horario',
        'descricao',
        'texto',
        'file_id',
        'banner_id',
        'url',
        'ativo',
    ];

    public function getRouteKeyName(): string {
        $identifier = Route::current()->parameters()['service'];

        if (!ctype_digit($identifier)) {
            return 'url';
        }

        return 'id';
    }

    public function file(): BelongsTo {
        return $this->belongsTo(File::class);
    }

    public function banner(): BelongsTo {
        return $this->belongsTo(File::class);
    }

    public function setFriendlyURL(string $url): void {
        try {
            $this->friendly_url = $url;
            $this->save();
        } catch (QueryException $e) {
            if ($e->errorInfo[1] === 1062) { // Unique constraint failed...
                $buffer = [];
                preg_match('/-(\d+)$/', $url, $buffer);

                if (isset($buffer[1])) {
                    $this->setFriendlyURL(preg_replace('/-\d+$/', NULL, $url) . '-' . (((int)$buffer[1]) + 1));
                }
                else {
                    $this->setFriendlyURL($url . '-1');
                }
            }
            else {
                throw $e;
            }
        }
    }
}
