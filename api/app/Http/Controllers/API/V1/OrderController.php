<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Mail\Orcamento;
use App\Models\CommonText;
use App\Models\Order;
use App\Models\OrderOptions;
use App\Models\OrderProduct;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{

    public function get(Request $request, Order $order): JsonResponse
    {
        if ($order->id) {
//            $order->orderProducts->orderOptions->where('product_id',$order->orderProducts->product_id);
            foreach ($order->orderProducts as $key1 => $produto){
                foreach($produto->orderOptions as $key2 => $option){
                    if($produto['product_id'] !== $option['product_id']){
                        unset($order->orderProducts[$key1]->orderOptions[$key2]);
                    }
                }

                $order->orderProducts[$key1]->orderOptions = array_values($order->orderProducts[$key1]->orderOptions->toArray());
            }
            return response()->json($order->toArray());
        }
        $request->validate([
            'per-page' => 'nullable|integer',
            'page' => 'nullable|integer'
        ]);

        $query = Order::query();

        $perPage = $request->get('per-page', NULL);
        $page = $request->get('page', 1);

        $query->orderBy('id', 'DESC');
        return response()->json($query->paginate($perPage, ['*'], 'page', $page));
    }

    public function post(Request $request): JsonResponse
    {
        $fields = $request->json()->all();
        $order = new Order($fields);

        $products = $fields['products'];

        unset($fields['products']);
        $order->save();

        foreach ($products as $key => $item) {
            $produtos[] = $item['id'];
        }

//        $order->products()->sync($produtos);

        $orderProducts = [];

        foreach ($products as $produto) {
            $orderProducts[] = new OrderProduct([
                'product_id' => $produto['id'],
                'name' => $produto['name'],
                'products' => $produto['products'],
                'quantity' => $produto['quantity'],
            ]);
        }

        $order->orderProducts()->saveMany($orderProducts);

        $order->load('orderProducts');


        /*
        foreach ($order->products as $produto){
//            $produto->load('options');
            foreach ($products as $item){
                //registra os produtos do orçamento
                if($produto->pivot->product_id === $item['id']) {
                    $produto->pivot->name = $item['name'];
                    $produto->pivot->quantity = $item['quantity'];
                    $produto->pivot->save();
                    break;
                }
            }
        }
        */

        $idOrder = $order->id;


        $email = CommonText::where('identifier', 'contact.email')->firstOrFail();
        $campos = $request->json()->all();
        foreach ($campos['products'] as $keys => $campo) {
            if (!isset($campos['products'][$keys]['opcoesImg'])) {
                $campos['products'][$keys]['opcoesImg'] = [''];
            }
            if (!isset($campos['products'][$keys]['opcoesName'])) {
                $campos['products'][$keys]['opcoesName'] = ['sem opção'];
            }

            foreach ($campo['categoria_option'] as $value) {
                if (isset($campo['opcoesImg'][$value['id']])) {
                    $orderOptions = new OrderOptions();
                    $orderOptions['order_id'] = $idOrder;
                    $orderOptions['product_id'] = $campo['id'];
                    $orderOptions['option_id'] = $campo['opcoes'][$value['id']];
                    $orderOptions['name'] = $value['name'] . ' - ' . $campo['opcoesName'][$value['id']];
                    $orderOptions->save();
                }
            }

        }

//        Mail::to('angelo@azulsystems.com.br')->send(new Orcamento($request->json()->all()));
        Mail::to($email->content)->send(new Orcamento($campos));

        return response()->json($order->toArray(), 201);
    }

    public function delete(Order $order)
    {
        $order->delete();
    }

}