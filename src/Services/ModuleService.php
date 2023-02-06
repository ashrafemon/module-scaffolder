<?php

namespace Leafwrap\ModuleScaffolder\Services;

class ModuleService
{
    public function fileDirectoryBuilder($dir, $name, $type = 'folder'): bool
    {
        if (!file_exists($dir . '/' . $name)) {
            return $type === 'file'
                ? file_put_contents($dir . '/' . $name, '')
                : mkdir($dir . '/' . $name);
        }
        return false;
    }
}
