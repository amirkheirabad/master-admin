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
    $hasAccess =
        $factor->user_id === auth()->id() ||
        optional($factor->store)->user_id === auth()->id();
    abort_unless($hasAccess, 403);
    if (!auth()->user()->hasRole('seller')) {
        abort(403);
    }
    $parsian = new Parsian($factor);
    return $parsian->pay();
}

    // برگشت از بانک
    public function verify(Request $request)
    {
        $factor = Factor::where('hash', $request->OrderId)->first();

        if (!$factor) {
            return redirect('/')->with('error', 'فاکتور یافت نشد');
        }

        return (new Parsian($factor))->verify($request);
    }

    public function payByHash($hash)
    {
        $factor = Factor::where('hash', $hash)->firstOrFail();
        return (new Parsian($factor))->pay();
    }

    public function success()
    {
        return view('templates.factor.payment.success');
    }

    public function fail()
    {
        return view('templates.factor.payment.fail');
    }
}
