<?php
namespace Taro\Libs\IOInterface;

class IOStream
{
    private $stdin;
    private $stdout;
    private $stderr;

    function __construct($rawInput = [])
    {
        $this->stdin = $rawInput['stdin'] ?? STDIN;
        $this->stdout = $rawInput['stdout'] ?? STDOUT;
        $this->stderr = $rawInput['stderr'] ?? STDERR;
    }

    public function getStdin()
    {
        return $this->stdin;
    }

    public function getStdout()
    {
        return $this->stdout;
    }

    public function getStderr()
    {
        return $this->stderr;
    }

}