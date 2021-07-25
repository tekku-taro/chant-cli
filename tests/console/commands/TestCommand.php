<?php
namespace Taro\Tests\Console\Commands;

use Taro\Libs\Command\Command;

class TestCommand extends Command
{
    public $signature = 'command:test';

    public $params = ['name','age'];

    public $flags = ['m','s','x'];

    public function handle()
    {
        
    }
}