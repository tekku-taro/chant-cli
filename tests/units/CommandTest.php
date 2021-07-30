<?php
require_once "vendor/autoload.php";

use PHPUnit\Framework\TestCase;
use Taro\Libs\Application;
use Taro\Libs\IOInterface\IOStream;
use Taro\Tests\Console\Commands\TestCommand;

class CommandTest extends TestCase
{
    /** @var TestCommand $commandObj */
    private $commandObj;

    private $stdin;
    private $stdout;

    /** @var Application $app */
    private $app;

    public function setUp():void
    {
        $args = [
            './chant-cli',
            'command:test',
            '--name=hoge',
            'alpha',
            'beta',
            '-x',
            '--age=30',
            '--is_test',
        ];

        $this->stdin = fopen('php://memory', 'w+');
        $this->stdout = fopen('php://memory', 'w+');    

        $stream = new IOStream([
            'stdin'=>$this->stdin,
            'stdout'=>$this->stdout
        ]);

        $this->app = new Application($stream, $args);
        $this->commandObj = $this->app->findCommand();
    }


    private function readLineStdout()
    {
        rewind($this->stdout);
        $outout = trim(fgets($this->stdout));
        rewind($this->stdout);
        return $outout;
    }

    private function readAllStdout()
    {
        rewind($this->stdout);
        $outout = stream_get_contents($this->stdout);
        rewind($this->stdout);
        return $outout;
    }


    private function writeStdin($text)
    {
        rewind($this->stdin);
        fwrite($this->stdin, $text);
        rewind($this->stdin);
    }


    public function testArgument()
    {
        $result = $this->commandObj->argument('arg1'); 
        $this->assertEquals("alpha", $result); 

        $result = $this->commandObj->argument('arg2'); 
        $this->assertEquals("beta", $result);      

        $result = $this->commandObj->argument('arg3');   
        $this->assertNull($result);      
    }

    public function testParameter()
    {
        $result = $this->commandObj->parameter('name'); 
        $this->assertEquals("hoge", $result); 

        $result = $this->commandObj->parameter('age'); 
        $this->assertEquals("30", $result);

        $result = $this->commandObj->parameter('is_test'); 
        $this->assertTrue($result);
    }

    public function testFlag()
    {
        $result = $this->commandObj->flag('x'); 
        $this->assertTrue($result);

        $result = $this->commandObj->flag('m'); 
        $this->assertFalse($result);

    }

    public function testTextMethods()
    {
        $this->commandObj->prompt();
        $result = $this->readLineStdout();

        $this->assertEquals('>>', $result);

        $this->commandObj->text('test');
        $result = $this->readLineStdout();

        $this->assertEquals('test', $result);

        $this->commandObj->textDanger('test');
        $result = $this->readLineStdout();

        $this->assertEquals("\033[31mtest\033[0m", $result);

        $this->commandObj->textWarning('test');
        $result = $this->readLineStdout();

        $this->assertEquals("\033[1;33mtest\033[0m", $result);

        $this->commandObj->textInfo('test');
        $result = $this->readLineStdout();

        $this->assertEquals("\033[32mtest\033[0m", $result);

        $this->commandObj->success('test');
        $result = $this->readLineStdout();

        $this->assertEquals("\033[42mtest\033[0m", $result);

        $this->commandObj->error('test');
        $result = $this->readLineStdout();

        $this->assertEquals("\033[41mtest\033[0m", $result);
    }

    public function testQuestion()
    {
        $this->writeStdin('答え');
        $result = $this->commandObj->question('question1?');

        $this->assertEquals("答え", $result);        
    }

    public function testConfirm()
    {
        $this->writeStdin('yes');
        $result = $this->commandObj->confirm('question1?');
        $question = $this->readLineStdout();
        $this->assertTrue($result);  
        $this->assertEquals("question1? (yes/no) default no", $question);  

        $this->writeStdin('y');
        $result = $this->commandObj->confirm('question1?');
        $this->assertTrue($result);      
        
        $this->writeStdin('no');
        $result = $this->commandObj->confirm('question1?');
        $this->assertFalse($result);      

        $this->writeStdin('awef');
        $result = $this->commandObj->confirm('question1?');
        $this->assertFalse($result);
    }

    public function testTable()
    {
        $headers = ['header1','header2','header3'];
        $body = [
            ['cell11','cell12','cell13'],
            ['cell21','cell22','cell23'],
            ['cell31','cell32','cell33'],
        ];
        $colorText = 'red';

        $this->commandObj->table($headers,$body,$colorText);
        $result = $this->readAllStdout();
        $expected = <<< TABLE
-------------------------------
| [31mheader1[0m | [31mheader2[0m | [31mheader3[0m |
-------------------------------
| cell11  | cell12  | cell13  |
| cell21  | cell22  | cell23  |
| cell31  | cell32  | cell33  |
-------------------------------

TABLE;
        $this->assertEquals($expected, $result);
    }
}
