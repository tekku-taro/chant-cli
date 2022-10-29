<?php
namespace Taro\Libs;

define('DS', DIRECTORY_SEPARATOR);

use Taro\App\Bootstrap\Config;
use Taro\Libs\Command\CallbackCommand;
use Taro\Libs\Command\Command;
use Taro\Libs\Command\CommandRegistry;
use Taro\Libs\IOInterface\Input;
use Taro\Libs\IOInterface\IOStream;

class Application
{
    public $stream;
    public $input;
    public $commandRegistry;
    public static $instance;

    public function __construct(IOStream $stream, array $args, $rootToFilePath = null)
    {
        $this->stream = $stream;
        $this->input = new Input($args);
        $this->commandRegistry = new CommandRegistry($this->stream, $this->input);
        self::$instance = $this;
    }

    public function loadConfigData($rootToFilePath)
    {
        Config::overwriteData($rootToFilePath);
        $this->commandRegistry = new CommandRegistry($this->stream, $this->input);
    }

    public static function getInstance()
    {
        return self::$instance;
    }

    public function run()
    {
        $this->executeCommand();
        $this->terminate();
    }

    public function registerCommand($commandName,\Closure $callback)
    {
        $command = new CallbackCommand($this->stream, $this->input);
        $command->callback = $callback;
        $this->commandRegistry->register($commandName, $command);
    }

    public function findCommand():Command
    {
        return $this->commandRegistry->getCommand($this->input);
    }

    private function executeCommand()
    {
        /** @var Command $command */
        $command = $this->findCommand();
        $command->setUp();
        $command->handle();
        $command->tearDown();
    }

    private function terminate()
    {
    }

}