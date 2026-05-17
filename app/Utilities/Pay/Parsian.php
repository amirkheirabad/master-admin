<?php
namespace App\Utilities\Pay;

use Modules\Factor\Models\Factor;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;

require_once('nusoap.php');

class Parsian
{
    private $factor;
    private $pin;
    
    public function __construct($factor)
    {
        $this->factor = $factor;
        $this->pin = env('PARSIAN_PIN');
    }

    public function pay()
    {
        if ($this->factor->price_status == 2) {
            return Redirect::route('factor-list')->with('error', 'این فاکتور قبلاً پرداخت شده است');
        }

        try {
            $client = new \SoapClient('https://pec.shaparak.ir/NewIPGServices/Sale/SaleService.asmx?wsdl', [
                'exceptions' => true,
                'trace' => true,
                'stream_context' => stream_context_create([
                    'ssl' => ['verify_peer' => false, 'verify_peer_name' => false]
                ])
            ]);
        } catch (\Exception $e) {
            Log::error('SOAP client error: ' . $e->getMessage());
            return Redirect::route('factor-list')->with('error', 'خطا در اتصال به بانک: ' . $e->getMessage());
        }

        $Amount = $this->factor->price;
        $OrderId = $this->factor->id;
        $CallBackUrl = route('factor.payment.verify');

        $params = [
            "LoginAccount" => $this->pin,
            "Amount" => $Amount,
            "OrderId" => $OrderId,
            "CallBackUrl" => $CallBackUrl
        ];

        try {
            $result = $client->SalePaymentRequest(['requestData' => $params]);
        } catch (\Exception $e) {
            Log::error('SalePaymentRequest error: ' . $e->getMessage());
            return Redirect::route('factor-list')->with('error', 'خطا در ارتباط با بانک: ' . $e->getMessage());
        }

        if (isset($result->SalePaymentRequestResult->Token) && $result->SalePaymentRequestResult->Status == 0) {
            $token = $result->SalePaymentRequestResult->Token;
            $this->factor->update([
                'payment_token' => $token,
                'price_status' => 0
            ]);
            // هدایت به درگاه
            return Redirect::to("https://pec.shaparak.ir/NewIPG/?Token=" . $token);
        } else {
            $message = $result->SalePaymentRequestResult->Message ?? 'خطای ناشناخته';
            Log::error('Payment request failed: ' . $message);
            return Redirect::route('factor-list')->with('error', 'پرداخت ناموفق: ' . $message);
        }
    }

    public function verify($request)
    {
        $token = $request->Token;
        $status = $request->status;
        $orderId = $request->OrderId;
        $RRN = $request->RRN;
        $pin = $this->pin;

        $factor = Factor::where('id', $orderId)->first();
        if (!$factor) {
            return Redirect::route('factor-list')->with('error', 'فاکتور یافت نشد');
        }

        if ($RRN > 0 && $status == 0) {
            try {
                $client = new \SoapClient("https://pec.shaparak.ir/NewIPGServices/Confirm/ConfirmService.asmx?WSDL", [
                    'exceptions' => true,
                    'trace' => true,
                    'stream_context' => stream_context_create([
                        'ssl' => ['verify_peer' => false, 'verify_peer_name' => false]
                    ])
                ]);
                $params = ["LoginAccount" => $pin, "Token" => $token];
                $result = $client->ConfirmPayment(['requestData' => $params]);
            } catch (\Exception $e) {
                Log::error('ConfirmPayment error: ' . $e->getMessage());
                return Redirect::route('factor-list')->with('error', 'خطا در تأیید پرداخت');
            }

            if ($result->ConfirmPaymentResult->Status == 0 && $result->ConfirmPaymentResult->RRN > 0) {
                $factor->update([
                    'price_status' => 2,
                    'paid_factor_date' => now(),
                    'payment_rrn' => $result->ConfirmPaymentResult->RRN,
                    'payment_card' => $result->ConfirmPaymentResult->CardNumberMasked ?? ''
                ]);
                return Redirect::route('factor-list')->with('success', 'پرداخت با موفقیت انجام شد');
            } else {
                $factor->update(['price_status' => 1]);
                return Redirect::route('factor-list')->with('error', 'پرداخت ناموفق');
            }
        } else {
            $factor->update(['price_status' => 1]);
            return Redirect::route('factor-list')->with('error', 'پرداخت توسط کاربر لغو شد');
        }
    }
}