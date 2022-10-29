<?php
namespace Taro\Libs\Exceptions;

use ErrorException;
use Taro\Libs\IOInterface\IOStream;
use Taro\Libs\IOInterface\Output;
use Taro\Libs\Signature\Signature;

class CommandArgumentMissingException extends ErrorException
{
    // 例外を再定義し、メッセージをオプションではなくする
    public function __construct(Signature $signature) {
        // なんらかのコード
        $message = $signature->command .' コマンドの必須引数( '. implode(',', $signature->requiredOptions) .' )が足りません。コマンドのシグネチャー（' . $signature->original . '）';
        
        $output = new Output(new IOStream);
        $output->printWithColor($message, 'red');
        // 全てを正しく確実に代入する
        parent::__construct($message);
    }
}