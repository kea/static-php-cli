<?php

declare(strict_types=1);

namespace SPC\builder\macos\library;

class libSDL_image extends MacOSLibraryBase
{
    use \SPC\builder\unix\library\libSDL_image;

    public const NAME = 'libSDL_image';

    protected function getDotCmakeFilesDir(): string
    {
        return BUILD_ROOT_PATH . '/SDL2_image.framework/Resources';
    }
}
