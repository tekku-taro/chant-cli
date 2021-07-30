<?php
namespace Taro\App\Console;

use Taro\App\Console\Commands\HelpCommand;
use Taro\App\Console\Commands\ListRegisteredCommands;
use Taro\App\Console\Commands\TestCommand;

class CommandList
{

    public static $commands = [
        // SomeCommand::class
        TestCommand::class,
        ListRegisteredCommands::class,
        HelpCommand::class,
    ];
}