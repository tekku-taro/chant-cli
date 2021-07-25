<?php
namespace Taro\App\Console\Commands;

use Taro\Libs\Command\Command;

class TestCommand extends Command
{
    public $signature = 'command:test [option1] [option2?]';

    public $params = ['param1','param2'];

    public $flags = ['f','s','x'];

    public function handle()
    {
        $this->output->print('this is the Test Command.');
    }
}