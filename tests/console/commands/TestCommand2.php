<?php
namespace Taro\Tests\Console\Commands;

use Taro\Libs\Command\Command;

class TestCommand2 extends Command
{
    public $signature = 'command:test2 [arg1] [arg2?] [arg3?]';

    public $params = ['name','age'];

    public $flags = ['m','s','x'];

    public $description = 'this is TestCommand2.';

    public function handle()
    {
        
    }
}