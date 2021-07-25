<?php
namespace Taro\App\Bootstrap;

use Exception;

class Config
{
    public static $data = [
        'default'=>[
            'commandlist_namespace' => \Taro\App\Console\CommandList::class
        ],
        'testing'=>[
            'commandlist_namespace' => \Taro\Tests\Console\CommandList::class
        ],
    ];

    public static function get($key)
    {
        $env = getenv('APP_ENV');
        if(!isset(self::$data[$env])) {
            $env = 'default';
        }

        $keys = explode('.', $key);

        return self::find(self::$data[$env], $keys); 
    }

    private static function find($data, $keys = [])
    {
        if(empty($keys)) {
            return $data;
        }

        $key = array_shift($keys);

        if(!isset($data[$key])) {
            throw new Exception();
        }

        return self::find($data[$key], $keys);
    }
    
}