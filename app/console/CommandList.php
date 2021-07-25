<?php
namespace Taro\App\Console;

use Taro\App\Console\Commands\TestCommand;

class CommandList
{

    public static $commands = [
        // SomeCommand::class
        TestCommand::class,
    ];
}