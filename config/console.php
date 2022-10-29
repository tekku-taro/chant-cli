<?php
namespace Taro\Config;


return [
    'default'=>[
        'commandlist_namespace' => \Taro\App\Console\CommandList::class,
        'commands_dir' => DS . 'app' . DS . 'console' . DS . 'commands'
    ],
];