<?php
namespace Taro\Libs\Command;

use ErrorException;
use Taro\App\Bootstrap\Config;
use Taro\Libs\Exceptions\CommandNotFoundException;
use Taro\Libs\IOInterface\Input;
use Taro\Libs\IOInterface\IOStream;
use Taro\Libs\Signature\Signature;
use Taro\Libs\Signature\SigParser;

class CommandRegistry
{
    public $signatureMap = [];

    private $commandListClass;

    private $commandClassList;
    
    private $sigParser;

    public function __construct(IOStream $stream, Input $input)
    {
        $this->sigParser = new SigParser;
        $this->loadCommands();
        $this->generateSignatureMap( $stream, $input);
    }

    private function loadCommands()
    {
        /** @var string $commandListClass  */
        $this->commandListClass = Config::get('commandlist_namespace');
        // $class = Taro\Tests\Console\CommandList::class;
        $this->commandClassList = $this->commandListClass::$commands;
    }

    public function register($commandText, Command $callbackCommand)
    {
        $signature = $this->makeSignatureFromCallback($commandText, $callbackCommand);
        $this->signatureMap[$signature->command] = $signature;  
    }

    private function generateSignatureMap(IOStream $stream, Input $input)
    {
        foreach ($this->commandClassList as $commandClass) {
            if(is_subclass_of($commandClass, Command::class)) {
                $commandObj = $this->instantiateCommand( $commandClass,  $stream,  $input);
                $signature = $this->makeSignature($commandObj);
                $this->signatureMap[$signature->command] = $signature;
            }
        }
    }

    private function makeSignature(Command $commandObj):Signature
    {
        return $this->sigParser->parseCommand($commandObj);
    }

    private function makeSignatureFromCallback($commandText, Command $callbackCommand):Signature
    {
        return $this->sigParser->parseCallback($commandText, $callbackCommand);
    }



    public function getCommand(Input $input):Command
    {
        $signature = $this->matchSignature($input);
        if($signature instanceof Signature) {
            return $signature->commandObj;
        }

        throw new CommandNotFoundException('コマンドが見つかりません。');
    }

    private function matchSignature(Input $input)
    {
        $matchedSignature = null;
        // パターンリストを一つずつ、INPUTとSignatureで比較する
        foreach ($this->signatureMap as $pattern => $signature) {
            // コマンドのみパターンが、INPUTシグネチャに含まれるか
            if(preg_match($pattern, $input->command, $matches)) {
                foreach ($matches as $key => $match) {
                    if(is_string($key)) {
                        $input->options[$key] = $match;
                    }
                }
                $matchedSignature = $signature;
                break;
            }
        }

        if($matchedSignature === null) {
            return false;
        }

        // あれば、param名とフラグ名があるかチェック、無ければエラー        
        $this->checkParams($matchedSignature, $input);

        return $matchedSignature;
    }

    private function checkParams(Signature $matchedSignature,Input $input)
    {
        foreach ($input->flags as $flag) {
            if(!in_array($flag, $matchedSignature->flags)) {
                throw new ErrorException('存在しないフラグです。：' . $flag);
            }
        }

        foreach ($input->params as $paramName => $value) {
            if(!in_array($paramName, $matchedSignature->params)) {
                throw new ErrorException('存在しないパラメータです。：' . $paramName);
            }
        }
    }

    private function instantiateCommand($namespace, IOStream $stream, Input $input):Command
    {
        $command = new $namespace($stream, $input);
        return $command;
    }

}