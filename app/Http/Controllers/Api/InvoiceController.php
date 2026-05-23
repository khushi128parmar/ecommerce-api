<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;

use Barryvdh\DomPDF\Facade\Pdf;

use App\Http\Controllers\Controller;

class InvoiceController extends Controller
{
    public function download(Order $order)
    {
        if ($order->user_id !== auth()->id()) {

            return response()->json([

                'success' => false,

                'message' => 'Unauthorized access'
            ], 403);
        }

        $pdf = Pdf::loadView(
            'invoices.order',
            compact('order')
        );

        return $pdf->download(
            'invoice-' . $order->id . '.pdf'
        );
    }
}