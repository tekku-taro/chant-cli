<?php
namespace Taro\Libs\IOInterface;

class Input
{
    public $argv;
    public $original;
    public $command;
    public $options = [];
    public $params = [];
    public $flags = [];


    public function __construct(array $args)
    {
        $this->parseInput($args);
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
            } elseif(preg_match('/^--([a-zA-Z0-9]+)$/', $part, $matches)) {
                $this->params[$matches[1]] = true;
            } elseif(preg_match('/^--([a-zA-Z0-9]+)=([a-zA-Z0-9]+)$/', $part, $matches)) {
                $this->params[$matches[1]] = $matches[2];
            } else {
                $commands[] = $part;
            }
        }
        $this->command = implode(' ', $commands);
    }
}