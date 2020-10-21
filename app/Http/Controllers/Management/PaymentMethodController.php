<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Management\PaymentMethod\SaveRequest;
use App\Http\Requests\Management\PaymentMethod\DeleteRequest;
use App\Models\PaymentMethod;

class PaymentMethodController extends Controller
{
    public function index()
    {
        if (!empty(request()->route('id'))) {
            $payment_method = PaymentMethod::find(request()->route('id'));

            if ($payment_method) {
                return view('management.payment_method.index', ['payment_method' => $payment_method]);
            }
        }

        return view('management.payment_method.index');
    }

    public function list()
    {
        $payment_methods = PaymentMethod::all();

        return view('management.payment_method.list', ['payment_methods' => $payment_methods]);
    }

    public function save(SaveRequest $request)
    {
        $payment_method = !empty($request->input('id')) ? PaymentMethod::find($request->input('id')) : new PaymentMethod();
        $payment_method->name = $request->input('name');
        $payment_method->is_credit = !empty($request->input('is_credit')) ? 1 : 0;
        $payment_method->save();

        return response()->json(['message' => 'Dados salvo'], 201);
    }

    public function delete(DeleteRequest $request)
    {
        $payment_method = PaymentMethod::find($request->input('id'));
        $payment_method->delete();

        return response()->json(['message' => 'Forma de pagamento excluída'], 201);
    }
}
