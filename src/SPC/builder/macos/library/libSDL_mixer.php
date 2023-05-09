<?php

declare(strict_types=1);

namespace SPC\builder\macos\library;

class libSDL_mixer extends MacOSLibraryBase
{
    use \SPC\builder\unix\library\libSDL_mixer;

    public const NAME = 'libSDL_mixer';
}
