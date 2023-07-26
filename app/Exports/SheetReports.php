<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\RichText\RichText;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class SheetReports
{
    protected $spreadsheet;
    protected $sheet;
    protected $options;
    protected $numberPages = 1;

    public function __construct(array $options = null)
    {
        ini_set("memory_limit", -1);
        ini_set('max_execution_time', 0);
        $this->options = $options;
        $this->spreadsheet = new Spreadsheet();
    }

    public function buildHeader($title, $titleSheet, $createSheet, $setActive, $arrayCellsValues)
    {
        if ($createSheet) {
            $this->sheet = $this->spreadsheet->createSheet($this->numberPages);
            $this->numberPages++;
        }

        $this->sheet = $this->spreadsheet->setActiveSheetIndex($setActive);
        $this->sheet = $this->spreadsheet->getActiveSheet()->setTitle($title);

        $richText = new RichText($this->spreadsheet->getActiveSheet()->getCell('A1'));
        $richText->createTextRun($titleSheet);

        $this->margeCell($setActive, 'A1:O1');

        foreach ($arrayCellsValues as $cellValue) {

            $this->sheet->setCellValueExplicit($cellValue['coordinate'], $cellValue['value'] . ' ', DataType::TYPE_STRING);
        }

    }

    public function buildData($setActive, $beginLine, $columnStyle, $arrayCellsValues)
    {
        $this->sheet = $this->spreadsheet->setActiveSheetIndex($setActive);

        foreach ($arrayCellsValues as $cellValues) {

            foreach ($cellValues as $value) {

                $this->sheet->setCellValueExplicit($value['coordinate'] . $beginLine, $value['value'], $value['type']);
            }
            $beginLine++;
        }
        $this->setStyle($columnStyle);
    }

    public function manualLine($setActive, $coordinate, $titleSheet)
    {
        $this->sheet = $this->spreadsheet->setActiveSheetIndex($setActive);
        $richText = new RichText($this->spreadsheet->getActiveSheet()->getCell($coordinate));
        $richText->createTextRun($titleSheet);
    }

    public function manualLineValue($setActive, $coordinate, $value, $type)
    {
        $this->sheet = $this->spreadsheet->setActiveSheetIndex($setActive);
        $this->sheet->setCellValueExplicit($coordinate, $value, $type);
    }

    public function manualSetColor($setActive, $line, $color)
    {
        $this->sheet = $this->spreadsheet->setActiveSheetIndex($setActive);
        $this->sheet->getStyle($line)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($color);
    }

    public function margeCell($setActive, $range)
    {
        $this->sheet = $this->spreadsheet->setActiveSheetIndex($setActive);
        $this->spreadsheet->getActiveSheet()->mergeCells($range);
    }
    public function manualSetBorder($setActive, $range, $borders = [])
    {
        //outline = borda em todas as direções
        $arrayBorders = [
            'borders' => []
        ];

        foreach ($borders as $key => $b) {
            $arrayBorders['borders'] += [
                $key => [
                    'borderStyle' => $b['style'],
                    'color' => ['argb' => $b['color']],
                ],
            ];
        }

        $this->sheet = $this->spreadsheet->setActiveSheetIndex($setActive);
        $this->spreadsheet->getActiveSheet()->getStyle($range)->applyFromArray($arrayBorders);
    }
    public function createSheet()
    {
        $this->sheet = $this->spreadsheet->createSheet($this->numberPages);
        $this->numberPages++;
    }

    public function setDirection($columns, $direction, $setActive)
    {
        $this->sheet = $this->spreadsheet->setActiveSheetIndex($setActive);
        $this->sheet->getStyle($columns)->getAlignment()->setHorizontal($direction);
    }

    private function setStyle($columnsStyle)
    {
        $this->sheet->setShowGridlines(false);
        $this->sheet->setAutoFilter($columnsStyle);
        $style = $this->sheet->getStyle($columnsStyle);
        $style->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
        $style->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('00222960');
        $endColumnIndex = Coordinate::columnIndexFromString($this->sheet->getHighestColumn());
        for ($i = 1; $i <= $endColumnIndex; ++$i) {
            $this->sheet->getColumnDimension(Coordinate::stringFromColumnIndex($i))->setAutoSize(true);
        }

    }

    public function buildReport()
    {
        $this->spreadsheet->setActiveSheetIndex(0);
        $writer = new Xlsx($this->spreadsheet);

        ob_start();
            $writer->save('php://output');
            $file = ob_get_contents();
        ob_end_clean();
        $nameFile = Session::get('usuario')->id_person . '_.xlsx';
        Storage::disk('DIR_ORD')->put('order/'.Session::get('usuario')->id_person.'/'. $nameFile, $file);

        return $nameFile;
    }
}
