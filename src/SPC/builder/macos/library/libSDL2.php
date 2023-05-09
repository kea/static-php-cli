<?php

declare(strict_types=1);

namespace SPC\builder\macos\library;

use SPC\exception\RuntimeException;
use SPC\store\FileSystem;

class libSDL2 extends MacOSLibraryBase
{
    use \SPC\builder\unix\library\libSDL2;

    public const NAME = 'libSDL2';

    /**
     * Add frameworks missing for a libSDL2 bug
     */
    protected function patchPkgconfFrameworks(): void
    {
        logger()->info('Patching library [' . static::NAME . '] pkgconfig');
        $name = 'sdl2.pc';
        $realpath = realpath(BUILD_ROOT_PATH . '/lib/pkgconfig/' . $name);
        if ($realpath === false) {
            throw new RuntimeException('Cannot find library [' . static::NAME . '] pkgconfig file [' . $name . '] !');
        }
        logger()->debug('Patching ' . $realpath);

        $file = FileSystem::readFile($realpath);

        if (preg_match('/^Libs:.*-framework,CoreVideo/m', $file) !== 1) {
            return;
        }

        if (preg_match('/^Libs:.*-framework,GameController/m', $file) !== 1) {
            $file = preg_replace(
                '/^Libs: (.*) -lm$/m',
                'Libs: $1 -Wl,-framework,GameController -lm',
                $file
            );
        }

        if (preg_match('/^Libs:.*-framework,CoreHaptics/m', $file) !== 1) {
            $file = preg_replace(
                '/^Libs: (.*) -lm$/m',
                'Libs: $1 -Wl,-weak_framework,CoreHaptics -lm',
                $file
            );
        }

        FileSystem::writeFile($realpath, $file);
    }
}
