<?php
/**
 * @author Ernesto Baez 
 */

namespace ErnestoBaezF\L5CoreToolbox\Console\Commands;


use Illuminate\Console\Command;

class ExampleCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sample:echo {param}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Example command';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $input = $this->ask("Text to print");

        $this->info($input);
        $this->line("=====");
        $this->info("Argument: ".$this->argument("param"));
    }
}
