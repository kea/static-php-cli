<?php

declare(strict_types=1);

namespace SPC\builder\unix\library;

use SPC\store\FileSystem;

trait libSDL2
{
    protected function build()
    {
        [, , $destdir] = SEPARATED_PATH;

        FileSystem::resetDir($this->source_dir . '/build');
        shell()->cd($this->source_dir . '/build')
            ->exec(
                "{$this->builder->configure_env} " . ' cmake ' .
                "{$this->builder->makeCmakeArgs()} " .
                '-DBUILD_SHARED_LIBS=OFF ' .
                '-DSDL_SYSTEM_ICONV=OFF ' .
                '-DSDL2_DISABLE_SDL2MAIN=ON ' .
                '-DSDL_TEST=OFF ' .
                '..'
            )
            ->exec("cmake --build . -j {$this->builder->concurrency}")
            ->exec("make install DESTDIR={$destdir}");

        $this->patchGeneratedCMackeFiles();
        $this->patchPkgconfPrefix(['sdl2.pc']);
        $this->patchPkgconfFrameworks();
    }

    protected function patchPkgconfFrameworks(): void
    {
    }

    protected function patchGeneratedCMackeFiles(): void
    {
        FileSystem::replaceFile(
            BUILD_LIB_PATH . '/cmake/SDL2/SDL2staticTargets-release.cmake',
            REPLACE_FILE_PREG,
            '|"/lib/libSDL2\.a"|',
            '"' . BUILD_LIB_PATH . '/libSDL2.a"'
        );
        FileSystem::replaceFile(
            BUILD_LIB_PATH . '/cmake/SDL2/SDL2staticTargets.cmake',
            REPLACE_FILE_PREG,
            '|INTERFACE_INCLUDE_DIRECTORIES "/include;/include/SDL2"|',
            'INTERFACE_INCLUDE_DIRECTORIES "' . BUILD_INCLUDE_PATH . ';' . BUILD_INCLUDE_PATH . '/SDL2"'
        );
    }
}
