<?php

declare(strict_types=1);

namespace SPC\builder\linux\library;

class libSDL2 extends LinuxLibraryBase
{
    use \SPC\builder\unix\library\libSDL2;

    public const NAME = 'libSDL2';
}
