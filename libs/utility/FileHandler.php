<?php
namespace Taro\Libs\Utility;

use Taro\Libs\Exceptions\FileNotFoundException;
use Taro\Libs\Exceptions\FileNotSavedException;

class FileHandler
{
    public const ROOT = __DIR__ . '..' .DS. '..';

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
}