<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Helpers\AuthHelper;
use App\Http\Helpers\TransactionHelper;
use App\Models\Receipt;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {

        $receipts = Receipt::with('account')->get();


        $admin = AuthHelper::getAdmin();

        return view('admin.order.index', compact('receipts', 'admin'));
    }

    public function confirm(Request $request, $id)
    {
        $params = $request->all();

        $receipt = Receipt::find($id);
        $receipt->status = 'confirmed';
        $receipt->amount = $params['amount'];
        $receipt->save();

        $receipts = Receipt::with('account')->get();

        TransactionHelper::addCoins($receipt->account_id, $receipt->amount);

        return json_encode([
            'id' => $id,
            'params' => $params,
            'receipts' => $receipts,
        ]);
    }

    public function unconfirm(Request $request, $id)
    {
        $params = $request->all();

        $receipt = Receipt::find($id);
        $receipt->status = 'rejected';

        $receipt->save();
        $receipts = Receipt::with('account')->get();

        return json_encode([
            'id' => $id,
            'params' => $params,
            'receipts' => $receipts
        ]);
    }
}
