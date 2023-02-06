<?php

namespace Leafwrap\ModuleScaffolder\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Leafwrap\ModuleScaffolder\Services\ModuleService;

class ModuleMake extends Command
{
    private string $moduleName;
    private bool $directoryRequired = false;
    private string $directoryName;
    private bool $jsRequired = false;
    private string $jsExtension = 'js';
    private bool $cssRequired = false;
    private string $cssExtension = 'css';


    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:make';

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
        try {
            $this->getAttributes();
            $this->moduleBuilder();
            $this->info('Module created successfully, Please insert the js & css file link to vite.config.js files array');
            return Command::SUCCESS;
        } catch (Exception $e) {
            $this->error($e->getMessage());
        }
    }

    private function itemAskingPrompt($message, $promptType = 'ask')
    {
        $temp = $promptType === 'confirm'
            ? $this->confirm($message)
            : $this->ask($message);

        if (gettype($temp) === 'boolean') {
            return $temp === 1 || $temp === true;
        } elseif ((gettype($temp) === 'string' && strlen($temp))) {
            return $temp;
        }

        return $this->itemAskingPrompt($message, $promptType);
    }

    private function getAttributes()
    {
        $this->moduleName = $this->itemAskingPrompt('What is your module name?');

        // Directory if module have a parent folder
        $this->directoryRequired = $this->itemAskingPrompt('Do you want to push your module in a directory?', 'confirm');
        if ($this->directoryRequired) {
            $this->directoryName = $this->itemAskingPrompt('Which directory do you want to push your module?');
        }

        // JS if module have js file
        $this->jsRequired = $this->itemAskingPrompt('Do you want js file in your module?', 'confirm');
        if ($this->jsRequired) {
            $this->jsExtension = $this->itemAskingPrompt('Which js extension you want (js/jsx etc.)?');
        }

        // CSS if module have css file
        $this->cssRequired = $this->itemAskingPrompt('Do you want css file in your module?', 'confirm');
        if ($this->cssRequired) {
            $this->cssExtension = $this->itemAskingPrompt('Which css extension you want (css/scss/sass etc.)?');
        }
    }

    private function moduleBuilder()
    {
        if ($this->directoryRequired && $this->directoryName) {
            if (!(new ModuleService())->fileDirectoryBuilder(base_path("modules"), $this->directoryName)) {
                $this->error('Directory folder already exists');
            }
        }

        $moduleName = $this->directoryRequired
            ? $this->directoryName . '/' . $this->moduleName
            : $this->moduleName;
        if (!(new ModuleService())->fileDirectoryBuilder(base_path("modules"), $moduleName)) {
            $this->error('Module folder already exists');
        }

        $moduleDirs = [
            ['is_required' => true, 'dirname' => 'html', 'filename' => 'index', 'extension' => 'blade.php'],
            ['is_required' => $this->cssRequired, 'dirname' => 'css', 'filename' => $this->moduleName, 'extension' => $this->cssExtension],
            ['is_required' => $this->jsRequired, 'dirname' => 'js', 'filename' => $this->moduleName, 'extension' => $this->jsExtension],
        ];

        foreach ($moduleDirs as $dir) {
            if ($dir['is_required']) {
//                if (!(new ModuleService())->fileDirectoryBuilder(base_path("modules/{$moduleName}"), $dir['dirname'])) {
//                    $this->error('Module item folder already exists');
//                }
//                $fileName = $dir['filename'] . ".{$dir['extension']}";
//                if (!(new ModuleService())->fileDirectoryBuilder(base_path("modules/{$moduleName}/{$dir['dirname']}"), $fileName, 'file')) {
//                    $this->error('Module item file already exists');
//                }

                // Make Module Item Directory
                (new ModuleService())->fileDirectoryBuilder(
                    base_path("modules/$moduleName"),
                    $dir['dirname']
                );

                // Make Module Item File
                $fileName = $dir['filename'] . ".{$dir['extension']}";
                (new ModuleService())->fileDirectoryBuilder(
                    base_path("modules/$moduleName/{$dir['dirname']}"),
                    $fileName, 'file'
                );
            }
        }
    }
}
