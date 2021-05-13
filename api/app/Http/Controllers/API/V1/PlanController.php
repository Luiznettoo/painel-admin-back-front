<?php

namespace App\Http\Controllers\API\V1;


use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function get(Request $request, Plan $plan): JsonResponse
    {
        if ($plan->id) {
            $plan->load('file.resources', 'topicos');
            return response()->json($plan->toArray());
        }
        $request->validate([
            'per-page' => 'nullable|integer',
            'page' => 'nullable|integer'
        ]);
        $query = Plan::with('file.resources','topicos');


        $perPage = $request->get('per-page', NULL);
        $page = $request->get('page', 1);
        $query->orderBy('id', 'desc');

        return response()->json($query->paginate($perPage, ['*'], 'page', $page));

    }

    public function post(Request $request): JsonResponse
    {

        $this->validateJSON([
            'title' => 'required|string|min:3',
            'description' => 'nullable',
            'price' => 'nullable|numeric',
            'topicos' => 'required|array',
        ]);

        $fields = $request->json()->all();
        $plan = new Plan($fields);
        $plan->save();

        $plan->topicos()->createMany($fields['topicos']);

        return response()->json($plan->toArray(), 201);
    }

    public function put(Request $request, Plan $plan): JsonResponse
    {
        $this->validateJSON([
            'title' => 'required|string|min:3',
            'description' => 'nullable',
            'price' => 'nullable|numeric',
            'topicos' => 'required|array',
        ]);


        $fields = $request->json()->all();
        $topicos = $fields['topicos'];
        unset($fields['topicos']);

        $plan->fill($fields);
        $plan->save();
        $plan->load('topicos');

        foreach ($plan->topicos as $topico) {
            $found = false;
            foreach ($topicos as $topicoBuffer) {
                if ($topico->id === $topicoBuffer['id']) {
                    $found = true;
                    $topico->topico = $topicoBuffer['topico'];
                    $topico->save();
                    break;
                }
            }
            if (!$found) {
                $topico->delete();
            }
        }

        foreach ($topicos as $topico) {
            if ($topico['id'] === -1) {
                $plan->topicos()->create($topico);
            }
        }

        return response()->json($plan->toArray(), 204);
    }

    public function delete(Plan $plan) {
        $plan->delete();
    }

}
