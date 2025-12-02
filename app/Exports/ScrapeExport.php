<?php

namespace App\Exports;

use App\Models\DemoScrape;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class ScrapeExport implements FromCollection, WithHeadings, WithColumnWidths
{
    public function collection()
    {
        return DemoScrape::all()->map(function ($item) {
            return [
                $item->id,
                $item->product_title,
                $item->product_price,
                $item->product_stock,
                $item->product_star_rating,
                $item->created_at,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'ID',
            'Title',
            'Price',
            'Stock',
            'Star Rating',
            'Created At'
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 10,
            'B' => 40,
            'C' => 15,
            'D' => 15,
            'E' => 20,
            'F' => 25,
        ];
    }
}
