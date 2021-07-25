<?php
namespace Taro\Libs\Command;

use ErrorException;
use Taro\Libs\Command\Command;

class CallbackCommand extends Command
{
    public $callback;

    public function handle()
    {
        if($this->callback instanceof \Closure) {
            call_user_func($this->callback, $this);
        }else {
            throw new ErrorException('コマンドとして与えられたCallbackを実行できませんでした。');
        }
    }
}