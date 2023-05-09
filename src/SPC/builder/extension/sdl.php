<?php

declare(strict_types=1);

namespace SPC\builder\extension;

use SPC\builder\Extension;
use SPC\exception\RuntimeException;
use SPC\util\CustomExt;

#[CustomExt('sdl')]
#[CustomExt('sdl_image')]
#[CustomExt('sdl_mixer')]
class sdl extends Extension
{
    public function getUnixConfigureArg(): string
    {
        return match ($this->name) {
            'sdl' => '--with-sdl',
            'sdl_image' => '--with-sdl_image=' . BUILD_ROOT_PATH,
            'sdl_mixer' => '--with-sdl_mixer=' . BUILD_ROOT_PATH,
            default => throw new RuntimeException('Not accept SDL extension'),
        };
    }
}
