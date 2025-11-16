<?php

namespace App\Http\Controllers;

use App\Models\Package;
use Ihasan\Bkash\Facades\Bkash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{

    public function processPayment(Request $request, $provider = 'bkash', $packageId)
    {
        if (!in_array($provider, ['bkash'])) {
            abort(404);
        }

        // Get the package
        $package = Package::findOrFail($packageId);

        if (!$package->is_active) {
            return back()->with('error', 'This package is not available.');
        }

        if ($provider === 'bkash') {
            $paymentData = [
                'amount'                  => $package->price, // Payment amount in BDT
                'payer_reference'         => 'pkg_' . $packageId . '_user_' . Auth::user()->id . '_' . time(), // Unique identifier for the payer
                'callback_url'            => route('bkash.callback'), //If you use this built in route then this package will handle your callback automatically otherwise you have to implement your own callback logic. So don't change this to use automatic callback handling
                'merchant_invoice_number' => 'INV-' . time(), // Unique invoice number
            ];


            try {
                $response = Bkash::createPayment($paymentData);
                // Redirect to the bKash payment page
                return redirect()->away($response['bkashURL']);
            } catch (\Exception $e) {
                return back()->with('error', $e->getMessage());
            }
        }

        return back()->with('error', 'Payment provider not supported.');
    }
}
