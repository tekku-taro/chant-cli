<?php
namespace Taro\Libs\IOInterface;

class Input
{
    public $argv;
    public $original;
    public $command;
    public $commandwithOptions;
    public $options = [];
    public $params = [];
    public $flags = [];


    public function __construct(array $args)
    {
        $this->parseInput($args);
    }


    public function hasOption($optionName)
    {
        if(isset($this->options[$optionName])) {
            return true;
        }

        return false;
    }

    public function getOption($optionName)
    {
        if($this->hasOption($optionName)) {
            return $this->options[$optionName];
        }

        return null;

    }

    public function hasParam($paramName)
    {
        if(isset($this->params[$paramName])) {
            return true;
        }

        return false;
    }

    public function param($paramName)
    {
        if($this->hasParam($paramName)) {
            return $this->params[$paramName];
        }

        return null;
    }

    public function hasFlag($flag)
    {
        if(in_array($flag, $this->flags)) {
            return true;
        }

        return false;
    }

    private function parseInput(array $args)
    {
        // $stdin = $stream->getStdin();
        // $line = trim(fgets($argc));
        array_shift($args);

        $this->original = implode(' ', $args);// trim(preg_replace('/(\.)?chant(\.php)?[\s]+/', '', $line));

        // $parts = explode(' ', $this->original);

        // $parts = array_filter($parts, function($part){
        //     return !empty($part);            
        // });
        foreach ($args as $part) {
            if(preg_match('/^-([a-zA-Z])$/', $part, $matches)) {
                $this->flags[] = $matches[1];
            } elseif(preg_match('/^--([a-zA-Z0-9_]+)$/', $part, $matches)) {
                $this->params[$matches[1]] = true;
            } elseif(preg_match('/^--([a-zA-Z0-9_]+)=([a-zA-Z0-9]+)$/', $part, $matches)) {
                $this->params[$matches[1]] = $matches[2];
            } else {
                $commands[] = $part;
            }
        }
        $this->commandwithOptions = implode(' ', $commands);
        $this->command = $commands[0];
    }
}