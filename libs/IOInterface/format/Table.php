<?php
namespace Taro\Libs\IOInterface\Format;

class Table
{
    private $rows = [];
    private $headers = [];
    private $colWidths;
    private $colLength = 0;
    private $rowWidth = 0;
    private $padding = 1;
    private $hBorder = '-';
    private $vBorder = '|';

    public function __construct(array $headers, array $data)
    {
        $this->headers = $headers;
        $this->rows = $data;
        $this->colLength = count($this->headers);

        $this->colWidths = array_fill_keys(range(0,$this->colLength -1), 0);
        $this->calcColWidths();
        $this->calcRowWidth();
    }

    public function toString()
    {
        $tableString = '';

        // headers
        $tableString .= $this->drawRow($this->headers) . PHP_EOL;
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

    private function drawRow($cells)
    {   
        $rowText = $this->vBorder;
        foreach ($cells as $colIdx => $cell) {
            $rowText .= $this->cellWithSpace($cell, $this->colWidths[$colIdx]);
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

    private function cellWithSpace($cellVal, $width)
    {
        $value = ' ';
        for ($i=0; $i < $width - 1; $i++) {
            if(isset($cellVal[$i])) {
                $value .= $cellVal[$i];
            }else {
                $value .= ' ';
            }
        }

        return $value;
    }

    private function calcRowWidth()
    {
        $this->rowWidth = array_reduce($this->colWidths, function($carry, $oneColWidth) {
            return $carry + $oneColWidth + $this->padding * 2 + 1;
        }, 1);
    }

    private function calcColWidths()
    {
        foreach ($this->rows as $row) {
            for ($idx=0; $idx < $this->colLength; $idx++) { 
                if(isset($row[$idx])) {
                    $length = strlen($row[$idx]);
                    if($this->colWidths[$idx] < $length) {
                        $this->colWidths[$idx] = $length;
                    }
                }
            }
        }
    }

}