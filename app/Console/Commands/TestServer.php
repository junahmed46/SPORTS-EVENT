<?php namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestServer extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
   
    protected $signature = 'test:data {sport_event}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run my command.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        echo $this->argument('sport_event');

        $this->info('test server started');

    }

}