<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Validator;
use Auth;

class OrderController extends Controller
{
    public function new()
    {
        return view('orders.new');
    }

    public function showList()
    {
        return view('orders.list', ['orders' => Order::all()]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'client_name' => 'required|min:3',
            'client_phone' => 'nullable|max:15',
            'product_name' => 'required|min:3',
            'product_amount' => 'required|integer|min:1',
            'total_value' => 'required|min:3',
            'payment_method' => 'required|min:3',
        ], [
            'client_name.required' => 'O nome do cliente é obrigatório',
            'client_name.min' => 'O nome do cliente informado é inválido',
            'client_phone.max' => 'O telefone do cliente nao pode ultrapassar 15 caracteres',
            'product_name.required' => 'O nome do produto é obrigatório',
            'product_name.min' => 'O nome do produto informado é inválido',
            'product_amount.required' => 'A quantidade é obrigatória',
            'product_amount.integer' => 'A quantidade informada é inválida',
            'product_amount.min' => 'A quantidade informada é inválida',
            'total_value.required' => 'O valor total é obrigatório',
            'total_value.min' => 'O valor total informado é inválido',
            'payment_method.required' => 'A forma de pagamento é obrigatória',
            'payment_method.min' => 'A forma de pagamento informada é inválida',
        ]);

        if ($validator->fails()) {
            $msg = "";
            foreach ($validator->errors()->all() as $error) {
                $msg .= "$error<br>";
            }
            return response()->json(['status' => 'error', 'message' => $msg], 200);
        }

        $order = new Order();
        $order->user_id = Auth::id();
        $order->client_name = $request->input('client_name');
        $order->client_phone = $request->input('client_phone');
        $order->product_name = $request->input('product_name');
        $order->product_amount = $request->input('product_amount');
        $order->notes = $request->input('notes');
        $order->total_value = $request->input('total_value');
        $order->payment_method = $request->input('payment_method');

        if ($order->save()) {
            return response()->json(['status' => 'success', 'message' => 'Pedido adicionado'], 200);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Não foi possível salvar o pedido'], 200);
        }
    }

    public function delete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|integer|exists:orders,id',
        ], [
            'order_id.required' => 'O ID do pedido é obrigatório',
            'order_id.integer' => 'O ID informado é inválido',
            'order_id.exist' => 'O produto informado não existe',
        ]);

        if ($validator->fails()) {
            $msg = "";
            foreach ($validator->errors()->all() as $error) {
                $msg .= "$error<br>";
            }
            return response()->json(['status' => 'error', 'message' => $msg], 200);
        }

        $order = Order::find($request->input('order_id'));
        if ($order->delete()) {
            return response()->json(['status' => 'success', 'message' => 'Pedido removido'], 200);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Não foi possível remover o pedido'], 200);
        }
    }
}
