<?php
require_once "vendor/autoload.php";

use PHPUnit\Framework\TestCase;
use Taro\Libs\Application;
use Taro\Libs\Command\CallbackCommand;
use Taro\Libs\IOInterface\IOStream;

class ApplicationTest extends TestCase
{
    private $stdin;
    private $stdout;
    private $stream;

    public function setUp():void
    {
        $this->stdin = fopen('php://memory', 'w+');
        $this->stdout = fopen('php://memory', 'w+');    

        $this->stream = new IOStream([
            'stdin'=>$this->stdin,
            'stdout'=>$this->stdout
        ]);

    }


    private function readLineStdout()
    {
        rewind($this->stdout);
        $outout = trim(fgets($this->stdout));
        rewind($this->stdout);
        return $outout;
    }


    public function testRun()
    {
        $args = [
            './chant-cli',
            'command:test',
            'alpha',
        ];

        $app = new Application($this->stream, $args);

        $output = 'this is TestCommand.';

        $app->run();
        $result = $this->readLineStdout();

        $this->assertEquals($output, $result);
    }

    public function testResgiterCommand()
    {
        $args = [
            './chant-cli',
            'somecommand',
        ];

        $app = new Application($this->stream, $args);

        $output = 'this is manually registered command';
        $app->registerCommand('somecommand', function(CallbackCommand $command) use($output){
            $command->text($output);
        });
        $app->run();
        $result = $this->readLineStdout();

        $this->assertEquals($output, $result);
    }

}
