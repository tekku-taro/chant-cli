<?php
namespace Taro\App\Console\Commands;

use Taro\Libs\Command\Command;

class TestCommand extends Command
{
    public $signature = 'command:test [option1] [option2?]';

    public $params = ['param1','param2'];

    public $flags = ['f','s','x'];

    public $description = 'command class for test';

    public function handle()
    {
        $this->output->printLine('this is the Test Command.');
        $this->textDanger('this is the red Command.');
        $this->success('this is the success Command.');
     
        $this->table(
            ['col1', 'col2'],
            [
                ['1cell1', '1cell2'],
                ['2cell1', '2cell2'],
                ['3cell1', '3cell2'],
                ['4cell1', '4cell2'],
            ],
            'green'
        );

        // $res = $this->question('question 1 ?');
        
        // $this->text($res);

        // if($this->confirm('are you sure ?')) {
        //     $this->text('Ok...');
        // } else {            
        //     $this->text('you are not sure.');
        // }

    }
}