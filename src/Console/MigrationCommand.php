<?php

namespace Yesccx\DatabaseLogger\Console;

use Illuminate\Console\Command;

class MigrationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'database-logger:migration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migration';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->comment('Publishing Migration...');
        $this->callSilent('vendor:publish', ['--tag' => 'database-logger-migrations']);

        $this->info('Migration created successfully!');
    }
}
