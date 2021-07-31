<?php
namespace Taro\Libs\Exceptions;

use ErrorException;

class CommandNotFoundException extends ErrorException
{
    protected $message = 'コマンドが見つかりません。Taro\App\Console\CommandList::$commandsにコマンドクラス名を追加しましたか？';
}