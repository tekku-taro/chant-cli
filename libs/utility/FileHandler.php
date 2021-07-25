<?php
namespace Taro\Libs\Utility;

use Taro\Libs\IOInterface\FileNotFoundException;
use Taro\Libs\IOInterface\FileNotSavedException;

class FileHandler
{
    public const ROOT = __DIR__ . '../../';

    public static $configPath = __DIR__ . '../../app/bootstrap/config.php';

    public static $commandsDir = __DIR__ . '../../app/console/commands';


    static public function saveAs($filePath, $data)
    {
        if(file_put_contents($filePath, $data) === false) {
            throw new FileNotSavedException();
        }

        return true;
    }

    static public function load($filePath)
    {
        if(!file_exists($filePath)) {
            throw new FileNotFoundException();
        }

        return file_get_contents($filePath);
    }
}