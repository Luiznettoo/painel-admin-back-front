<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Validator;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    public function validateJSON(array $rules): void {
        $validator = Validator::make(request()->json()->all(), $rules);

        if ($validator->fails()) {
            response()->json($validator->errors()->toArray(), 422)->send();

            exit;
        }

        $params = array_keys(request()->json()->all());
        $validParams = array_keys($rules);

        foreach ($params as $param) {
            if (!in_array($param, $validParams, true)) {
                request()->json()->remove($param);
            }
        }
    }
}
