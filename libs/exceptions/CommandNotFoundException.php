<?php
namespace Taro\Libs\Exceptions;

use ErrorException;
use Taro\Libs\IOInterface\IOStream;
use Taro\Libs\IOInterface\Output;

class CommandNotFoundException extends ErrorException
{
    // 例外を再定義し、メッセージをオプションではなくする
    public function __construct(string $commandListClassName) {
        // なんらかのコード
        $message = 'コマンドが見つかりません。'. $commandListClassName .'::$commandsにコマンドクラス名を追加しましたか？';
        
        $output = new Output(new IOStream());
        $output->printWithColor($message, 'red');
        // 全てを正しく確実に代入する
        parent::__construct($message);
    }

}