<?php

namespace Leafwrap\ModuleScaffolder\Console\Commands;

use Illuminate\Console\Command;

class ModuleScaffold extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:scaffold';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Frontend module scaffold generator';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if (!file_exists(base_path("modules"))) {
            mkdir(base_path('modules'));

            $moduleRoutes = <<<TEXT
            <?php
            use Illuminate\Support\Facades\Route;

            Route::get('modules', function(){
                return 'modules routes';
            });
            TEXT;
            file_put_contents(base_path('modules/web.php'), $moduleRoutes);

            $viteContent = <<<TEXT
            import { defineConfig } from "vite";
            import laravel from "laravel-vite-plugin";

            let files = [
                // base files
                "resources/css/app.css",
                "resources/js/app.js",
            ];

            export default defineConfig({
                plugins: [
                    laravel({
                        input: files,
                        refresh: true,
                    }),
                ],
            });
            TEXT;
            file_put_contents(base_path('vite.config.js'), $viteContent);
            $this->info('Scaffold generate successfully, Please use this command npm install & npm run dev');
        } else {
            $this->error('Sorry, Scaffold already generated. Otherwise delete the modules folders');
        }
        return Command::SUCCESS;
    }
}
