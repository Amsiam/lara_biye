<?php

namespace App\Http\Controllers\Vendor\Bkash;

use App\Mail\InvoiceMail;
use App\Models\Connection;
use App\Models\Package;
use App\Models\Purchase;
use App\Models\User;
use Ihasan\Bkash\Facades\Bkash;
use Ihasan\Bkash\Models\BkashPayment;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class BkashController extends Controller
{
    /**
     * Handle the callback from bKash
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function callback(Request $request)
    {
        $paymentId = $request->input('paymentID');
        $status = $request->input('status');

        if ($status !== 'success') {
            return redirect()->route('bkash.failed')
                ->with('error', 'Payment was not successful')
                ->with('payment_id', $paymentId);
        }


        try {
            $payment = BkashPayment::where('payment_id', $paymentId)
                ->where('transaction_status', 'Completed')
                ->first();

            if ($payment && !empty($payment->trx_id)) {
                $response = Bkash::queryPayment($paymentId);
            } else {
                $response = Bkash::executePayment($paymentId);
            }

            // Extract package ID and user ID from payerReference
            // payerReference format: pkg_{packageId}_user_{userId}_{timestamp}
            $payerReference = $response['payerReference'] ?? "0_0_0_0_0";
            $payerReferenceParts = explode('_', $payerReference);
            $packageId = isset($payerReferenceParts[1]) ? $payerReferenceParts[1] : null;
            $userId = isset($payerReferenceParts[3]) ? $payerReferenceParts[3] : null;

            if ($userId && $packageId) {
                $package = Package::find($packageId);
                $user = User::find($userId);

                if ($package && $user) {
                    // Find existing purchase record or create new one
                    $purchase = Purchase::where('payment_id', $paymentId)->first();

                    if (!$purchase) {
                        // Create new purchase record if it doesn't exist
                        $purchase = Purchase::create([
                            'user_id' => $userId,
                            'package_id' => $packageId,
                            'amount' => $package->price,
                            'transaction_id' => $response['trxID'] ?? null,
                            'payment_id' => $response['paymentID'] ?? null,
                            'invoice_number' => $response['merchantInvoiceNumber'] ?? null,
                            'payment_method' => 'bkash',
                            'status' => Purchase::STATUS_PENDING,
                            'payment_stage' => Purchase::STAGE_PENDING,
                            'connections_purchased' => $package->connections,
                            'payment_response' => $response,
                            'payment_initiated_at' => now(),
                        ]);
                    }

                    // Check if payment was actually successful
                    $transactionStatus = $response['transactionStatus'] ?? null;

                    if ($transactionStatus === 'Completed') {
                        // Mark purchase as completed
                        $purchase->update([
                            'transaction_id' => $response['trxID'] ?? null,
                            'payment_stage' => Purchase::STAGE_COMPLETED,
                            'status' => Purchase::STATUS_COMPLETED,
                            'payment_response' => $response,
                            'payment_completed_at' => now(),
                        ]);

                        // Apply connections ONLY if payment is completed and not already applied
                        if (!$purchase->connections_applied) {
                            $purchase->applyConnections();
                        }

                        // Send invoice email
                        try {
                            Mail::to($user->email)->send(new InvoiceMail($user, $package, $response));
                        } catch (\Exception $e) {
                            // Log the error but don't fail the payment
                            Log::error('Failed to send invoice email: ' . $e->getMessage());
                        }
                    } else {
                        // Payment execution failed
                        $purchase->markAsFailed('Payment execution failed. Status: ' . $transactionStatus);
                    }
                }
            }

            $successUrl = config('bkash.redirect_urls.success');
            if ($successUrl) {
                return redirect()->to($successUrl)
                    ->with('payment', $response);
            }

            return redirect()->route('bkash.success')
                ->with('payment', $response);
        } catch (\Exception $e) {
            // Mark purchase as failed
            if (isset($paymentId)) {
                $purchase = Purchase::where('payment_id', $paymentId)->first();
                if ($purchase) {
                    $purchase->markAsFailed($e->getMessage());
                }
            }

            $failedUrl = config('bkash.redirect_urls.failed');
            if ($failedUrl) {
                return redirect()->to($failedUrl)
                    ->with('error', $e->getMessage())
                    ->with('payment_id', $paymentId);
            }

            return redirect()->route('bkash.failed')
                ->with('error', $e->getMessage())
                ->with('payment_id', $paymentId);
        }
    }

    /**
     * Display success page
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function success(Request $request)
    {

        $payment = $request->session()->get('payment');


        return view('bkash::success', compact('payment'));
    }

    /**
     * Display failed page
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function failed(Request $request)
    {
        $error = $request->session()->get('error');
        $paymentId = $request->session()->get('payment_id');

        return view('bkash::failed', compact('error', 'paymentId'));
    }
}
