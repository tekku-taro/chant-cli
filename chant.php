#!c:\xampp\php\php.exe
<?php
require_once './vendor/autoload.php';

use Taro\Libs\Application;
use Taro\Libs\Command\CallbackCommand;
use Taro\Libs\IOInterface\IOStream;


$stream = new IOStream;

$app = new Application($stream, $argv);

$app->registerCommand('somecommand', function(CallbackCommand $command){
    $command->textInfo('this is manually registered command');
});

$app->run();