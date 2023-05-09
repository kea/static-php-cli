<?php

declare(strict_types=1);

namespace SPC\builder\unix\library;

use SPC\store\FileSystem;

trait libSDL_mixer
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
                '-DCMAKE_BUILD_TYPE=SDL2-static ' .
                '-DSDL2MIXER_SAMPLES=OFF ' .
                '-DSDL2MIXER_OPUS=OFF ' .
                '-DSDL2MIXER_FLAC=OFF ' .
                '-DSDL2MIXER_MOD=OFF ' .
                '-DSDL2MIXER_MIDI_FLUIDSYNTH=OFF ' .
                '..'
            )
            ->exec("cmake --build . -j {$this->builder->concurrency}")
            ->exec("make install DESTDIR={$destdir}");

        $this->fixPathToGeneratedCMakeFiles();
    }

    protected function fixPathToGeneratedCMakeFiles(): void
    {
        FileSystem::replaceFile(
            BUILD_LIB_PATH . '/cmake/SDL2_mixer/SDL2_mixer-static-targets-sdl2-static.cmake',
            REPLACE_FILE_PREG,
            '|"/lib/libSDL2_mixer\.a"|',
            '"' . BUILD_LIB_PATH . '/libSDL2_mixer.a"'
        );
        FileSystem::replaceFile(
            BUILD_LIB_PATH . '/cmake/SDL2_mixer/SDL2_mixer-static-targets.cmake',
            REPLACE_FILE_PREG,
            '|INTERFACE_INCLUDE_DIRECTORIES "/include/SDL2"|',
            'INTERFACE_INCLUDE_DIRECTORIES "' . BUILD_INCLUDE_PATH . '/SDL2"'
        );
    }
}
