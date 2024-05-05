<?php

declare(strict_types=1);

/*
 * Copyright © 2019 Dxvn, Inc. All rights reserved.
 *
 * © Tran Ngoc Duc <ductn@diepxuan.com>
 *   Tran Ngoc Duc <caothu91@gmail.com>
 */

use Composer\InstalledVersions as ComposerPackage;

if (!function_exists("module_path")) {
    function module_path($package_name, $path = null)
    {
        $packagePath = new \SplFileInfo(ComposerPackage::getInstallPath($package_name));
        $packagePath = $packagePath->isDir() ? $packagePath : new \SplFileInfo(__DIR__ . '/../');

        if ($path) {
            return Str::of($packagePath->getRealPath())
                                ->explode(DIRECTORY_SEPARATOR)
                                ->push(Str::of($path)->trim()->explode(DIRECTORY_SEPARATOR))
                                ->flatten()
                                ->implode(DIRECTORY_SEPARATOR);
        }
        return $packagePath->getRealPath();
    }
}
