<?php
namespace Taro\Libs\Signature;


use Taro\Libs\Command\Command;

class SigParser
{
    public function parseCommand(Command $commandObj):Signature
    {
        $signature = new Signature();

        $signature->original = trim($commandObj->signature);
        $signature->description = $commandObj->description;
        $signature->params = $commandObj->params;
        $signature->flags = $commandObj->flags;   
        $signature->commandObj = $commandObj;   
        
        $this->addSignaturePattern($signature);

        return $signature;
    }

    public function parseCallback($commandText, Command $callbackCommand):Signature
    {
        ['command' => $command, 'options' => $options, 'requiredOptions' => $requiredOptions, 'count' => $count] = $this->getCommandOptionsAndArgCount($commandText);

        $pattern = $this->createSignaturePattern($commandText);

        $signature = new Signature();

        $signature->original = $commandText;  
        $signature->commandObj = $callbackCommand;
        $signature->commandPattern = $pattern;   
        $signature->command = $command;
        $signature->options = $options;
        $signature->requiredOptions = $requiredOptions;
        $signature->commandArgCount = $count;

        return $signature;
    }


    private function addSignaturePattern(Signature $signature)
    {
        $commandText = $signature->original;

        ['command' => $command, 'options' => $options, 'requiredOptions' => $requiredOptions, 'count' => $count] = $this->getCommandOptionsAndArgCount($commandText);


        $signature->commandPattern = $this->createSignaturePattern($commandText);
        $signature->command = $command;
        $signature->options = $options;
        $signature->requiredOptions = $requiredOptions;
        $signature->commandArgCount = $count;         
    }

    private function getCommandOptionsAndArgCount($commandText)
    {
        $parts = explode(' ', $commandText);

        $parts = array_filter($parts, function($part){
            return !empty($part);            
        });
        $options = [];
        $requiredOptions = [];
        $command = '';
        $count = 0;
        foreach ($parts as $part) {
            if(preg_match('/\[([a-zA-Z0-9-?_]+)\]/', $part, $matches)) {
                $options[] = $matches[1];
                if(strpos($matches[1],'?') !== (strlen($matches[1]) -1)) {
                    $requiredOptions[] = $matches[1];
                    $count += 1;
                }
            } else {
                $command .= $part;
                $count += 1;
            }
        }
        return ['command' => $command, 'options' => $options ,'requiredOptions' => $requiredOptions, 'count' => $count];
    }

    private function createSignaturePattern($commandText)
    {

        // Convert argument e.g. [arg1]
        $commandText = preg_replace('/\[([a-zA-Z0-9_]+)\]/', '(?P<\1>[a-zA-Z0-9-_]+)',$commandText);

        // Convert optional argument e.g. [arg1?]
        $commandText = preg_replace('/\[([a-zA-Z0-9_]+)\?\]/', '(?P<\1>[a-zA-Z0-9-_]*)',$commandText);

        // Convert whitespace e.g. ' '
        $commandText = preg_replace('/[ 　]+/', '[\s]*',$commandText);

        // Add start and end delimiters, and case insensitive flag
        $commandText = '/^' . $commandText . '$/i';

        return $commandText;
    }    
}