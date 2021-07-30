<?php
namespace Taro\App\Console\Commands;

use Taro\Libs\Command\Command;

class HelpCommand extends Command
{
    public $signature = 'command:help';

    public $params = [];

    public $flags = [];

    public $description = 'show help information';

    public function handle()
    {
        $this->textInfo('Help command (' . $this->signature . ')');
        $this->textInfo('Command usage:');
        $this->text('linux   >> ./chant command [argument] --param --param=value -flag');
        $this->text('windows >> php chant.php command [argument] --param --param=value -flag');
        
        $this->text('');
        $this->textInfo('Available command list:');
        $listCommand = new ListRegisteredCommands($this->stream, $this->input);

        $listCommand->handle();

    }
}