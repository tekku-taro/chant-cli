<?php
namespace Taro\App\Console\Commands;

use Taro\Libs\Application;
use Taro\Libs\Command\Command;
use Taro\Libs\Signature\Signature;

class ListRegisteredCommands extends Command
{
    public $signature = 'command:list';

    public $params = [];

    public $flags = [];

    public $description = 'list up registered commands';

    public function handle()
    {
        $list = Application::getInstance()->commandRegistry->getCommandList();
        $body = [];
        /** @var Signature $signature */
        foreach ($list as $command => $signature) {
            $body[] = [
                $signature->original,
                $signature->description,
                $this->arrayToDispString($signature->params),
                $this->arrayToDispString($signature->flags),
            ];
        }

        $this->table(
            ['command', 'description','parameters', 'flags'],
            $body,
            'green'
        );
    }

    private function arrayToDispString(array $array)
    {
        return implode(', ', $array);
    }
}