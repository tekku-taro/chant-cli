<?php
namespace Taro\Libs\Command;

use Taro\Libs\IOInterface\Format\ColorScheme;
use Taro\Libs\IOInterface\Input;
use Taro\Libs\IOInterface\IOStream;
use Taro\Libs\IOInterface\Output;

abstract class Command
{

    public $signature;

    public $params = [];

    public $flags = [];

    protected $stream;

    protected $input;

    protected $output;

    public function __construct(IOStream $stream, Input $input)
    {
        $this->stream = $stream;
        $this->input = $input;
        $this->output = new Output($stream);
    }

    public function setUp(){}

    abstract public function handle();

    public function tearDown(){}

    public function prompt()
    {
        $this->output->print('>>');
    }

    public function text($text)
    {
        $this->output->printLine($text);
    }

    public function textDanger($text)
    {
        $this->output->printWithColor($text, 'red');
    }

    public function textWarning($text)
    {
        $this->output->printWithColor($text, 'yellow');
    }

    public function textInfo($text)
    {
        $this->output->printWithColor($text, 'green');
    }

    public function success($text)
    {
        $this->output->printWithBackgroundColor($text, 'green');
    }

    public function error($text)
    {
        $this->output->printWithBackgroundColor($text, 'red');
    }

    public function question($text)
    {
        $this->text($text);
        $this->prompt();
        return trim(fgets($this->stream->getStdin()));
    }

    public function confirm($text)
    {
        $response = $this->question($text . ' (yes/no) default no');

        if($response === 'yes' | $response === 'y') {
            return true;
        }

        return false;
    }

    public function table($headers, $body, $colorText = null)
    {
        $this->output->printTable($headers, $body, $colorText);
    }



}