<?php

namespace App\Services;

use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Notifications\Notification;
use ZipArchive;

class PdfService
{
    private function generateInvoicePdf(Invoice $invoice)
    {
        return Pdf::loadView('pdf.invoice', compact('invoice'))
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'defaultFont' => 'DejaVu Sans',
                'isRemoteEnabled'      => true,
                'isHtml5ParserEnabled' => true,
                'isPhpEnabled'         => true,
                'dpi' => 150,
                'chroot'               => public_path(),
            ]);
    }

    public function downloadInvoicePdf(Invoice $invoice)
    {
        try {
            $pdf = $this->generateInvoicePdf($invoice);

            Notification::make()
                ->title('PDF Generated Successfully')
                ->success()
                ->send();

            return response()->streamDownload(
                fn() => print($pdf->output()),
                "invoice-{$invoice->invoice_number}-" . now()->format('is') . ".pdf",
                ['Content-Type' => 'application/pdf'],
            );
        } catch (\Exception $e) {
            Notification::make()
                ->title('PDF Generation Failed')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function generateBulkInvoicePdfs($invoices)
    {
        try {
            $zip         = new ZipArchive();
            $zipFileName = 'invoices-' . now()->format('Y-m-d-H-i-s') . '.zip';
            $zipPath     = storage_path('app/temp/' . $zipFileName);

            // Create temp directory if it doesn't exist
            if (! file_exists(storage_path('app/temp'))) {
                mkdir(storage_path('app/temp'), 0755, true);
            }

            if ($zip->open($zipPath, ZipArchive::CREATE) === true) {
                foreach ($invoices as $invoice) {
                    $pdf = $this->generateInvoicePdf($invoice);
                    $zip->addFromString(
                        "invoice-{$invoice->invoice_number}-" . now()->format('is') . ".pdf",
                        $pdf->output()
                    );
                }
                $zip->close();

                Notification::make()
                    ->title('PDFs Generated Successfully')
                    ->success()
                    ->send();

                return response()->download($zipPath, $zipFileName)->deleteFileAfterSend();
            }
        } catch (\Exception $e) {
            Notification::make()
                ->title('Bulk PDF Generation Failed')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }
}
