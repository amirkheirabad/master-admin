<?php

namespace Modules\Factor\Controllers\Web;
use Illuminate\Http\Request;
use Modules\Factor\Models\Factor;
use App\Utilities\Pay\Parsian;
use App\Http\Controllers\Controller;


class PaymentController extends Controller
{
    // درخواست پرداخت
    public function pay($id)
{
    $factor = Factor::findOrFail($id);
    if (!auth()->user()->hasRole('seller')) {
        abort(403);
    }
    $parsian = new Parsian($factor);
    return $parsian->pay();
}
    
    // برگشت از بانک
    public function verify(Request $request)
    {
        $factor = Factor::where('id', $request->OrderId)->first();
        if (!$factor) {
            return redirect('/')->with('error', 'فاکتور یافت نشد');
        }
        
        $parsian = new Parsian($factor);
        return $parsian->verify($request);
    }
}