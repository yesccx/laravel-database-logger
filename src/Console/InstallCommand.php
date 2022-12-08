<?php

namespace Yesccx\DatabaseLogger\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'database-logger:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->comment('Publishing Service Provider...');
        $this->callSilent('vendor:publish', ['--tag' => 'database-logger-provider']);

        $this->comment('Publishing Configuration...');
        $this->callSilent('vendor:publish', ['--tag' => 'database-logger-config']);

        $this->registerDatabaseLoggerServiceProvider();

        $this->info('Database Logger installed successfully.');
    }

    /**
     * Register the Database-Logger service provider in the application configuration file.
     *
     * @return void
     */
    protected function registerDatabaseLoggerServiceProvider()
    {
        $namespace = Str::replaceLast('\\', '', $this->laravel->getNamespace());

        $appConfig = file_get_contents(config_path('app.php'));

        if (Str::contains($appConfig, $namespace . '\\Providers\\DatabaseLoggerProvider::class')) {
            return;
        }

        $lineEndingCount = [
            "\r\n" => mb_substr_count($appConfig, "\r\n"),
            "\r"   => mb_substr_count($appConfig, "\r"),
            "\n"   => mb_substr_count($appConfig, "\n"),
        ];

        $eol = array_keys($lineEndingCount, max($lineEndingCount))[0];

        file_put_contents(config_path('app.php'), str_replace(
            "{$namespace}\\Providers\RouteServiceProvider::class," . $eol,
            "{$namespace}\\Providers\RouteServiceProvider::class," . $eol . "        {$namespace}\Providers\DatabaseLoggerProvider::class," . $eol,
            $appConfig
        ));

        file_put_contents(app_path('Providers/DatabaseLoggerProvider.php'), str_replace(
            "namespace App\Providers;",
            "namespace {$namespace}\Providers;",
            file_get_contents(app_path('Providers/DatabaseLoggerProvider.php'))
        ));
    }
}
