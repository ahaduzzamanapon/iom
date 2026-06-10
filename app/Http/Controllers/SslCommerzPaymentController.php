<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Karim007\SslcommerzLaravel\Facade\SSLCommerzPayment;
use Karim007\SslcommerzLaravel\SslCommerz\SslCommerzNotification;

class SslCommerzPaymentController extends Controller
{
    /**
     * Initiate payment from student panel.
     */
    public function index(Request $request)
    {
        $request->validate([
            'payment_id' => 'required|exists:payments,id',
        ]);

        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'দয়া করে লগইন করুন।');
        }

        $payment = Payment::where('id', $request->input('payment_id'))
            ->where('student_id', auth()->id())
            ->firstOrFail();

        if ($payment->status === 'paid') {
            return redirect()->route('student.payments.index')->with('info', 'পেমেন্টটি ইতিমধ্যে পরিশোধ করা হয়েছে।');
        }

        $post_data = [];
        $post_data['total_amount'] = $payment->amount;
        $post_data['currency'] = "BDT";
        $post_data['tran_id'] = $payment->invoice_number;

        $user = auth()->user();
        $profile = $user->studentProfile;

        $customer = [];
        $customer['name'] = $user->name;
        $customer['email'] = $user->email;
        $customer['address_1'] = $profile->address ?? 'Dhaka, Bangladesh';
        $customer['address_2'] = "";
        $customer['city'] = "Dhaka";
        $customer['state'] = "Dhaka";
        $customer['postcode'] = "1000";
        $customer['country'] = "Bangladesh";
        $customer['phone'] = $profile->phone ?? '01700000000';
        $customer['fax'] = "";

        $s_info = [];
        $s_info['shipping_method'] = 'No';
        $s_info['num_of_item'] = 1;
        $s_info['ship_name'] = $user->name;
        $s_info['ship_add1'] = $profile->address ?? 'Dhaka';
        $s_info['ship_add2'] = '';
        $s_info['ship_city'] = 'Dhaka';
        $s_info['ship_state'] = 'Dhaka';
        $s_info['ship_postcode'] = '1000';
        $s_info['ship_country'] = 'Bangladesh';

        $sslc = new SslCommerzNotification();
        $sslc->setCustomerInfo($customer)->setShipmentInfo($s_info);

        try {
            $payment_options = $sslc->makePayment($post_data, 'hosted');
            return $payment_options;
        } catch (\Exception $e) {
            return redirect()->route('student.payments.index')
                ->with('error', 'পেমেন্ট গেটওয়েতে সমস্যা হয়েছে: ' . $e->getMessage());
        }
    }

    /**
     * SSLCommerz success callback.
     */
    public function success(Request $request)
    {
        $tran_id = $request->input('tran_id');
        $amount = $request->input('amount');
        $currency = $request->input('currency');

        $payment = Payment::where('invoice_number', $tran_id)->first();
        if (!$payment) {
            return redirect()->route('student.payments.index')->with('error', 'পেমেন্ট রেকর্ড পাওয়া যায়নি।');
        }

        if ($payment->status === 'pending' || $payment->status === 'partial' || $payment->status === 'failed') {
            try {
                $validation = SSLCommerzPayment::orderValidate($request->all(), $tran_id, $amount, $currency);

                if ($validation) {
                    $payment->update([
                        'paid_amount' => $amount,
                        'status' => 'paid',
                        'payment_method' => 'sslcommerz',
                        'transaction_id' => $request->input('bank_tran_id'),
                        'paid_at' => now(),
                    ]);
                    return redirect()->route('student.payments.index')->with('success', 'পেমেন্ট সফলভাবে সম্পন্ন হয়েছে।');
                }
            } catch (\Exception $e) {
                return redirect()->route('student.payments.index')->with('error', 'ভ্যালিডেশন ত্রুটি: ' . $e->getMessage());
            }
        } elseif ($payment->status === 'paid') {
            return redirect()->route('student.payments.index')->with('success', 'পেমেন্ট ইতিমধ্যে সফলভাবে পরিশোধ করা হয়েছে।');
        }

        return redirect()->route('student.payments.index')->with('error', 'পেমেন্ট ভ্যালিডেশন ব্যর্থ হয়েছে।');
    }

    /**
     * SSLCommerz failure callback.
     */
    public function fail(Request $request)
    {
        $tran_id = $request->input('tran_id');
        $payment = Payment::where('invoice_number', $tran_id)->first();
        if ($payment && $payment->status === 'pending') {
            $payment->update(['status' => 'failed']);
        }
        return redirect()->route('student.payments.index')->with('error', 'পেমেন্ট ব্যর্থ হয়েছে। আবার চেষ্টা করুন।');
    }

    /**
     * SSLCommerz cancellation callback.
     */
    public function cancel(Request $request)
    {
        return redirect()->route('student.payments.index')->with('warning', 'পেমেন্ট বাতিল করা হয়েছে।');
    }

    /**
     * SSLCommerz IPN (Instant Payment Notification) callback.
     */
    public function ipn(Request $request)
    {
        if ($request->input('tran_id')) {
            $tran_id = $request->input('tran_id');
            $amount = $request->input('amount');
            $currency = $request->input('currency');

            $payment = Payment::where('invoice_number', $tran_id)->first();
            if ($payment && ($payment->status === 'pending' || $payment->status === 'partial' || $payment->status === 'failed')) {
                try {
                    $validation = SSLCommerzPayment::orderValidate($request->all(), $tran_id, $amount, $currency);
                    if ($validation) {
                        $payment->update([
                            'paid_amount' => $amount,
                            'status' => 'paid',
                            'payment_method' => 'sslcommerz',
                            'transaction_id' => $request->input('bank_tran_id'),
                            'paid_at' => now(),
                        ]);
                        return response()->json(['status' => 'success', 'message' => 'IPN processed successfully']);
                    }
                } catch (\Exception $e) {
                    return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
                }
            }
        }
        return response()->json(['status' => 'error', 'message' => 'Invalid data'], 400);
    }
}
