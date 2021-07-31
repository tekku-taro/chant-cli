<?php
namespace Taro\App\Console\Commands;

use Taro\Libs\Command\Command;
use Taro\Libs\Utility\FileHandler;

class MakeCommand extends Command
{
    public $signature = 'command:make [commandName]';

    public $params = [];

    public $flags = [];

    public $description = 'create custom command from template';

    public function handle()
    {
        $this->textInfo('make command (' . $this->signature . ')');

        $commandName = $this->argument('commandName');
        $command = $this->toSnakeCase($commandName);
        
        $template = FileHandler::load(FileHandler::$templatePath);
        $classData = $this->makeCommandFromTemplate($template, [
            'commandName'=>$commandName,
            'command'=>$command,
        ]);

        $filePath = FileHandler::$commandsDir . DS . $commandName . '.php';

        FileHandler::saveAs($filePath, $classData);

        $this->textInfo($commandName . ' class created at ' . $filePath);
        $this->textWarning('Taro\App\Console\CommandList::$commandsに作成したクラス名を追加して下さい。');
    }

    private function makeCommandFromTemplate($template, $vars)
    {
        foreach ($vars as $varName => $value) {
            $template = str_replace('[['.$varName.']]', $value, $template);
        }
        return $template;
    }

    private function toSnakeCase($value)
    {
        $value = preg_replace('/([A-Z])/', '_$1', $value);
        $value = strtolower($value);
        return ltrim($value, '_');
    }
}