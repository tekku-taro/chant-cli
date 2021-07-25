<?php
namespace Taro\Libs\Signature;


use Taro\Libs\Command\Command;

class SigParser
{
    public function parseCommand(Command $commandObj):Signature
    {
        $signature = new Signature();

        $signature->original = trim($commandObj->signature);
        $signature->params = $commandObj->params;
        $signature->flags = $commandObj->flags;   
        $signature->commandObj = $commandObj;   
        
        $this->addSignaturePattern($signature);

        return $signature;
    }

    public function parseCallback($commandText, Command $callbackCommand):Signature
    {
        $pattern = $this->createSignaturePattern($commandText);

        $signature = new Signature();

        $signature->original = $commandText;  
        $signature->commandObj = $callbackCommand;
        $signature->command = $pattern;   
        
        return $signature;
    }


    private function addSignaturePattern(Signature $signature)
    {
        $command = $signature->original;

        $parts = explode(' ', $command);

        $parts = array_filter($parts, function($part){
            return !empty($part);            
        });
        $options = [];
        $count = 0;
        foreach ($parts as $part) {
            if(preg_match('/\[([a-zA-Z0-9-?]+)\]/', $part, $matches)) {
                $options[] = $matches[1];
                if(strpos($matches[1],'?') !== (strlen($matches[1]) -1)) {
                    $count += 1;
                }
            } else {
                $count += 1;
            }
        }


        $signature->command = $this->createSignaturePattern($command);
        $signature->options = $options;
        $signature->commandArgCount = $count;         
    }

    private function createSignaturePattern($commandText)
    {

        // Convert argument e.g. [arg1]
        $commandText = preg_replace('/\[([a-zA-Z0-9]+)\]/', '(?P<\1>[a-zA-Z0-9-]+)',$commandText);

        // Convert optional argument e.g. [arg1?]
        $commandText = preg_replace('/\[([a-zA-Z0-9]+)\?\]/', '(?P<\1>[a-zA-Z0-9-]*)',$commandText);

        // Convert whitespace e.g. ' '
        $commandText = preg_replace('/[ 　]+/', '[\s]*',$commandText);

        // Add start and end delimiters, and case insensitive flag
        $commandText = '/^' . $commandText . '$/i';

        return $commandText;
    }    
}