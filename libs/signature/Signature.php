<?php
namespace Taro\Libs\Signature;

use Taro\Libs\Command\Command;

class Signature
{
    public $original;
    public $command;
    public $commandPattern;
    public $description;
    public $options = [];
    public $requiredOptions = [];
    public $params = [];
    public $flags = [];
    public $commandArgCount = 0;

    /** @var Command|null */
    public $commandObj = null;
}