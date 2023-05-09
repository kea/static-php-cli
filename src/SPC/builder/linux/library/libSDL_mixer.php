<?php

declare(strict_types=1);

namespace SPC\builder\linux\library;

class libSDL_mixer extends LinuxLibraryBase
{
    use \SPC\builder\unix\library\libSDL_mixer;

    public const NAME = 'libSDL_mixer';
}
