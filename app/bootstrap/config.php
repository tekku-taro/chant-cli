<?php
namespace Taro\App\Bootstrap;

use Taro\Libs\Utility\FileHandler;

class Config
{
    public static $data = [
        'default'=>[
            'commandlist_class' => \Taro\App\Console\CommandList::class,
        ],
        'testing'=>[
            'commandlist_class' => \Taro\Tests\Console\CommandList::class
        ],
    ];

    public static function overwriteData(string $rootToFilePath):void
    {
        $configPath = FileHandler::configPath($rootToFilePath);
        if($configPath === null) {
            return;
        }
        $updateData = include($configPath);
        self::$data = array_merge_recursive(self::$data, $updateData); 
        array_walk(self::$data, function(&$v) {
            if(is_array($v)) {
                $v = array_map(function($item) {
                    if (is_array($item)) {
                        $filtered = array_unique($item);
                        if(count($filtered) === 1) {
                            return $filtered[0];
                        }
                        return $filtered;
                    }
                    return $item;
                }, $v);
            }
        });
    }

    /**
     * @param string $key
     * @return mixed|null
     */
    public static function get($key)
    {
        $env = getenv('APP_ENV');
        if(!isset(self::$data[$env])) {
            $env = 'default';
        }

        $keys = explode('.', $key);

        return self::find(self::$data[$env], $keys); 
    }

    /**
     * @param string $key
     * @return mixed|null
     */
    public static function getLast($key)
    {
        $configData = self::get($key); 
        if(is_array($configData)) {
            return $configData[array_key_last($configData)];
        }
        return $configData;
    }

    private static function find($data, $keys = [])
    {
        if(empty($keys)) {
            return $data;
        }

        $key = array_shift($keys);

        if(!isset($data[$key])) {
            return null;
        }

        return self::find($data[$key], $keys);
    }
    
}