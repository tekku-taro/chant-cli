<?php
namespace Taro\Libs\IOInterface\Format;

class Table
{
    private $rows = [];
    private $headers = [];
    private $headerColor = null;
    private $colWidths;
    private $colLength = 0;
    private $rowWidth = 0;
    private $padding = 1;
    private $hBorder = '-';
    private $vBorder = '|';

    public function __construct(array $headers, array $data, $colorText = null)
    {
        $this->headerColor = $colorText;

        $this->headers = $headers;
        $this->rows = $data;
        $this->colLength = count($this->headers);

        $this->colWidths = array_fill_keys(range(0,$this->colLength -1), 0);
        $data[] = $headers;
        $this->calcColWidths($data);
        $this->calcRowWidth();
    }

    public function toString()
    {
        $tableString = '';

        //separator
        $tableString .= $this->fillForLength($this->hBorder, $this->rowWidth) . PHP_EOL;
        // headers
        $tableString .= $this->drawRow($this->headers, $this->headerColor) . PHP_EOL;
        //separator
        $tableString .= $this->fillForLength($this->hBorder, $this->rowWidth) . PHP_EOL;
        //body
        foreach ($this->rows as $row) {
            //row
            $tableString .= $this->drawRow($row) . PHP_EOL;
        }
        //bottomline
        $tableString .= $this->fillForLength($this->hBorder, $this->rowWidth) . PHP_EOL;

        return $tableString;
    }

    private function drawRow($cells, $colorText = null)
    {   
        $rowText = $this->vBorder;
        foreach ($cells as $colIdx => $cell) {
            $rowText .= $this->cellWithSpace($cell, $this->colWidths[$colIdx], $colorText) . $this->vBorder;
        }

        return $rowText;
    }

    private function fillForLength($letter, $length)
    {
        $value = '';
        for ($i=0; $i < $length; $i++) { 
            $value .= $letter;
        }

        return $value;
    }

    private function cellWithSpace($cellVal, $width, $colorText =null)
    {
        $value = ' ' . ColorScheme::encodeText($cellVal, $colorText);
        for ($i=strlen($cellVal); $i < $width-1; $i++) {
            $value .= ' ';
        }

        return $value;
    }

    private function calcRowWidth()
    {
        $this->rowWidth = array_reduce($this->colWidths, function($carry, $oneColWidth) {
            return $carry + $oneColWidth + 1;
        }, 1);
    }

    private function calcColWidths(array $data)
    {
        foreach ($data as $row) {
            for ($idx=0; $idx < $this->colLength; $idx++) { 
                if(isset($row[$idx])) {
                    $length = strlen($row[$idx]) + $this->padding * 2;
                    if($this->colWidths[$idx] < $length) {
                        $this->colWidths[$idx] = $length;
                    }
                }
            }
        }
    }

}