<?php

namespace Leafwrap\ModuleScaffolder\Console\Commands;

use Illuminate\Console\Command;

class ModuleMake extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:make {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Specific module structure generator';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $name = $this->argument("name");
        $dirs = [$name, 'html', 'css', 'js'];

        if (!file_exists(base_path("modules/$name"))) {
            foreach ($dirs as $key => $dir) {
                if ($key === 0) {
                    mkdir(base_path('modules') . '/' . $dir);
                } else {
                    mkdir(base_path("modules/$name") . '/' . $dir);
                    if ($dir === 'html') {
                        $fileName = 'index.blade.php';
                        file_put_contents(base_path("modules/$name/$dir/$fileName"), '');
                    } elseif ($dir === 'css') {
                        $fileName = strtolower($name . '.css');
                        file_put_contents(base_path("modules/$name/$dir/$fileName"), '');
                    } elseif ($dir === 'js') {
                        $fileName = strtolower($name . '.js');
                        file_put_contents(base_path("modules/$name/$dir/$fileName"), '');
                    }
                }
            }

            $this->info('Module created successfully, Please insert the js & css file link to vite.config.js files array');
        } else {
            $this->error('Module already exists');
        }
        return Command::SUCCESS;
    }
}
