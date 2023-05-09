<?php

declare(strict_types=1);

namespace SPC\builder\unix\library;

use SPC\store\FileSystem;

trait libSDL_image
{
    protected function build()
    {
        [$lib, $include, $destdir] = SEPARATED_PATH;

        FileSystem::resetDir($this->source_dir . '/build');
        shell()->cd($this->source_dir . '/build')
            ->exec(
                "{$this->builder->configure_env} " . ' cmake ' .
                "{$this->builder->makeCmakeArgs()} " .
                '-DBUILD_SHARED_LIBS=OFF ' .
                '-DSDL2_DIR=' . BUILD_ROOT_PATH . ' ' .
                '-DSDL2IMAGE_SAMPLES=OFF ' .
                '-DSDL2IMAGE_BACKEND_IMAGEIO=OFF ' .
                '..'
            )
            ->exec("cmake --build . -j {$this->builder->concurrency}")
            ->exec("make install DESTDIR={$destdir}");

        $this->fixPathToGeneratedCMakeFiles();
    }

    abstract protected function getDotCmakeFilesDir(): string;

    protected function fixPathToGeneratedCMakeFiles(): void
    {
        FileSystem::replaceFile(
            $this->getDotCmakeFilesDir() . '/SDL2_image-static-targets-release.cmake',
            REPLACE_FILE_PREG,
            '|list\(APPEND _cmake_import_check_files_for_SDL2_image::SDL2_image-static "/lib/libSDL2_image\.a" \)|',
            'list(APPEND _cmake_import_check_files_for_SDL2_image::SDL2_image-static "' . BUILD_LIB_PATH . '/libSDL2_image.a" )'
        );
        FileSystem::replaceFile(
            $this->getDotCmakeFilesDir() . '/SDL2_image-static-targets.cmake',
            REPLACE_FILE_PREG,
            '|INTERFACE_INCLUDE_DIRECTORIES "/include/SDL2"|',
            'INTERFACE_INCLUDE_DIRECTORIES "' . BUILD_INCLUDE_PATH . '/SDL2"'
        );
    }
}
