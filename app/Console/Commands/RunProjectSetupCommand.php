<?php


namespace App\Console\Commands;


use Illuminate\Console\Command;

class RunProjectSetupCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'project:setup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup and run the project';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Шаг 1: Установка зависимостей (Composer)');
        $this->call('composer:install');

        $this->info('Шаг 2: Установка зависимостей (NPM)');
        $this->call('npm:install');

        $this->info('Шаг 3: Выполнение миграций');
        $this->call('migrate');

        $this->info('Шаг 4: Сборка проекта');
        $this->call('npm:run', ['script' => 'dev']);

        $this->info('Шаг 5: Запуск проекта');
        $this->call('serve');
    }

}
