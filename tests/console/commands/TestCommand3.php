<?php
namespace Taro\Tests\Console\Commands;

use Taro\Libs\Command\Command;

class TestCommand3 extends Command
{
    public $signature = 'command:test3 [arg1] [arg2?] [arg3?]';

    public $params = ['name','age'];

    public $flags = ['m','s','x'];

    public $description = 'this is TestCommand3.';

    public function handle()
    {
        
    }
}