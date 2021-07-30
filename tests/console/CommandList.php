<?php
namespace Taro\Tests\Console;

use Taro\Tests\Console\Commands\TestCommand;
use Taro\Tests\Console\Commands\TestCommand2;
use Taro\Tests\Console\Commands\TestCommand3;

class CommandList
{

    public static $commands = [
        // SomeCommand::class
        TestCommand::class,
        TestCommand2::class,
        TestCommand3::class,
    ];
}