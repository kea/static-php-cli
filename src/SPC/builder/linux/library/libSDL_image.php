<?php

declare(strict_types=1);

namespace SPC\builder\linux\library;

class libSDL_image extends LinuxLibraryBase
{
    use \SPC\builder\unix\library\libSDL_image;

    public const NAME = 'libSDL_image';

    protected function getDotCmakeFilesDir(): string
    {
        return BUILD_LIB_PATH . '/cmake/SDL2_image';
    }
}
