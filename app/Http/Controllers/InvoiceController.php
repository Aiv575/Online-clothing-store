<?php

namespace App\Http\Controllers;


use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Receipt;
use App\Models\ReturnRequest;

class InvoiceController extends Controller
{
    public function generateInvoice(Receipt $receipt)
    {
        $pdf = Pdf::loadView('invoices.invoice', compact('receipt'));

        return $pdf->download('Чек на покупку.pdf');
    }

    public function generateInvoiceRefund($id)
    {
        $return = ReturnRequest::findOrFail($id);

        $pdf = Pdf::loadView('invoices.invoice_refund', ['return' => $return]);

        return $pdf->download('Чек на возврат.pdf');
    }
}
