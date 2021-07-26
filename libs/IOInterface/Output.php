<?php
namespace Taro\Libs\IOInterface;

use Taro\Libs\IOInterface\Format\ColorScheme;
use Taro\Libs\IOInterface\Format\Table;

class Output
{

    private $stream;

    public function __construct(IOStream $stream)
    {
        $this->stream = $stream;
    }

    private function stdout($text)
    {
        fwrite($this->stream->getStdout(), $text);
    }

    public function printLine($text)
    {
        $this->stdout($text . PHP_EOL);
    }

    public function print($text)
    {
        $this->stdout($text);
    }

    public function printWithColor($text, $colorText)
    {
        $this->stdout(ColorScheme::encodeText($text, $colorText) . PHP_EOL);
    }

    public function printWithBackgroundColor($text, $colorText)
    {
        $this->stdout(ColorScheme::encodeText($text, null, $colorText) . PHP_EOL);
    }

    private function createTable(array $headers,array $data, $colorText = null):Table
    {
        $table = new Table($headers, $data, $colorText);

        return $table;
    }

    public function printTable(array $headers,array $data, $colorText = null)
    {
        $table = $this->createTable($headers, $data, $colorText);
        $strTable = $table->toString();

        $this->print($strTable);
    }

}