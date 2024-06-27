<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PDFGeneratorController extends Controller
{
    public function __invoke(Sale $record)
    {
        return Pdf::loadView('/sale', ['record' => $record])
            ->download('abn_sales_invoice_' . $record->id . '.pdf');
    }
}
