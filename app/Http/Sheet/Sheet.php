<?php

namespace App\Http\Sheet;

use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Facades\Excel;

class Sheet
{
    public string $title; // Judul dari sheet
    public string $text;    // Teks tambahan yang akan ditampilkan di sheet
    public string $footer;  // Footer yang akan ditampilkan di sheet
    public array $header;   // Header dari sheet, biasanya berupa array yang berisi nama kolom
    public array $data; // Data yang akan ditampilkan di sheet, biasanya berupa array dari array
    public string $filename;    // Nama file yang akan digunakan saat mengunduh sheet

    public function __construct(array $params)
    {
        $this->title    = $params['title'] ?? 'Untitled';
        $this->text     = $params['text'] ?? '';
        $this->footer   = $params['footer'] ?? '';
        $this->header   = $params['header'] ?? [];
        $this->data     = $params['data'] ?? [];
        $this->filename = $params['filename'] ?? 'no name';
    }

    public function view()
    {
        return view('sheet.pdf', [
            'title' => $this->title,
            'text' => $this->text,
            'footer' => $this->footer,
            'header' => $this->header,
            'data' => $this->data,
        ]);
    }
    public function toPdf()
    {
        $pdf = Pdf::loadHTML($this->view()->render());
        $pdf->setPaper('A4', 'portrait');
        $pdf->setOption('isRemoteEnabled', true);
        $pdf->render();

        // return $pdf->download($this->filename . '.pdf');
        return $pdf->stream(
            $this->filename . '.pdf'
        );
    }
    public function toXls()
    {
        $sheetData = $this->data;
        $headers = $this->header;

        $export = new class($sheetData, $headers) implements FromArray, WithHeadings {
            protected array $data;
            protected array $headers;

            public function __construct(array $data, array $headers)
            {
                $this->data = $data;
                $this->headers = $headers;
            }

            public function array(): array
            {
                return $this->data;
            }

            public function headings(): array
            {
                return $this->headers;
            }
        };

        return Excel::download($export, $this->filename . '.xlsx');
    }
}
