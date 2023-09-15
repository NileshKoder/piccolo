<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BaseExport implements FromCollection, WithHeadings, WithStyles
{
    private $collection;
    private $headings;
    private $columnSizes;
    private $rowStyles;

    public function __construct($collection, $headings, $columnSizes, $rowStyles)
    {
        $this->collection = $collection;
        $this->headings = $headings;
        $this->columnSizes = $columnSizes;
        $this->rowStyles = $rowStyles;
    }

    public function collection(): Collection
    {
        return $this->collection;
    }

    public function headings(): array
    {
        return $this->headings;
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet): array
    {
        foreach ($this->columnSizes as $columnPos => $columnSize) {
            $columnDimension = $sheet->getColumnDimensionByColumn($columnPos);
            if ($columnSize == 'auto') {
                $columnDimension->setAutoSize(true);
            } else {
                $columnDimension->setWidth($columnSize);
            }
        }

        foreach ($this->rowStyles as $rowStyle) {
            if (!empty($rowStyle['row_styles'])) {
                foreach ($rowStyle['row_styles'] as $rowStylePos => $rowStyle) {
                    $getStyle = $sheet->getStyle($rowStylePos);
                    if (!empty($rowStyle['borders'])) {
                        $getStyle->applyFromArray(['borders' => $rowStyle['borders']]);
                    }
                    if (!empty($rowStyle['wrap_text'])) {
                        $getStyle->getAlignment()->setWrapText(true);
                    }
                }
            }
        }

        return $this->rowStyles;
    }
}
