<?php
namespace Taro\Libs\Utility;

use Taro\App\Bootstrap\Config;
use Taro\Libs\Exceptions\FileNotFoundException;
use Taro\Libs\Exceptions\FileNotSavedException;

class FileHandler
{
    public static $configPath = __DIR__  .DS. '..' .DS. '..' .DS. 'app' .DS. 'bootstrap' .DS. 'config.php';

    public static $commandsDir = __DIR__ .DS. '..' .DS. '..' .DS. 'app' .DS. 'console' .DS. 'commands';
    
    public static $templatePath = __DIR__  .DS. '..' .DS. 'command' .DS. 'CommandTemplate.php.dist';

    static public function saveAs($filePath, $data)
    {
        if(file_put_contents($filePath, $data) === false) {
            throw new FileNotSavedException($filePath . 'にファイルを保存できませんでした。');
        }

        return true;
    }

    static public function load($filePath)
    {
        if(!file_exists($filePath)) {
            throw new FileNotFoundException($filePath . 'のファイルが見つかりません。');
        }

        return file_get_contents($filePath);
    }

    private static function rootPath()
    {
        return dirname(\Composer\Factory::getComposerFile());
    }    

    public static function configPath($rootToFilePath = DS . 'config' . DS .'console.php')
    {
        $path = self::rootPath() . $rootToFilePath;
        if(!realpath($path)) {
            return null;
            // $path = self::$configPath;
        }
        return $path;
    }    

    public static function commandsDirectry()
    {
        $registeredCommandsDir = Config::get('commands_dir');
        $path = self::rootPath() . $registeredCommandsDir;
        if(!realpath($path)) {
            $path = self::$commandsDir;
        }
        return $path;
    }    
}