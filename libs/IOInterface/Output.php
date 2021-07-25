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

    public function print($text)
    {
        $this->stdout($text . PHP_EOL);
    }

    public function printWithColor($text, $colorCode)
    {
        $this->stdout(ColorScheme::encodeText($text, $colorCode) . PHP_EOL);
    }

    public function printWithBackgroundColor($text, $colorCode)
    {
        $this->stdout(ColorScheme::encodeText($text, null, $colorCode) . PHP_EOL);
    }

    private function createTable(array $headers,array $data):Table
    {
        $table = new Table($headers, $data);

        return $table;
    }

    public function printTable(array $headers,array $data)
    {
        $table = $this->createTable($headers, $data);
        $strTable = $table->toString();

        $this->print($strTable);
    }

}