<?php
namespace Taro\Tests\Console\Commands;

use Taro\Libs\Command\Command;

class TestCommand extends Command
{
    public $signature = 'command:test [arg1] [arg2?] [arg3?]';

    public $params = ['name','age','is_test'];

    public $flags = ['m','s','x'];

    public $description = 'this is TestCommand.';

    public function handle()
    {
        $this->text($this->description);
    }
}