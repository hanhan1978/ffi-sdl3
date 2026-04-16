<?php
declare(strict_types=1);

namespace SDL3\Internal;

use SDL3\Exception\LibraryNotFoundException;

final class LibraryFinder
{
    private const DEFAULT_SDL3_CANDIDATES = [
        '/opt/homebrew/lib/libSDL3.dylib',
        '/opt/homebrew/opt/sdl3/lib/libSDL3.dylib',
        '/usr/local/lib/libSDL3.dylib',
        '/usr/local/opt/sdl3/lib/libSDL3.dylib',
    ];

    private const DEFAULT_SDL3_TTF_CANDIDATES = [
        '/opt/homebrew/lib/libSDL3_ttf.dylib',
        '/opt/homebrew/opt/sdl3_ttf/lib/libSDL3_ttf.dylib',
        '/usr/local/lib/libSDL3_ttf.dylib',
        '/usr/local/opt/sdl3_ttf/lib/libSDL3_ttf.dylib',
    ];

    /**
     * @param list<string> $paths
     */
    public static function find(string $label, array $paths): string
    {
        foreach ($paths as $path) {
            $resolved = self::resolveCandidate($path);
            if ($resolved !== null) {
                return $resolved;
            }
        }

        throw new LibraryNotFoundException(sprintf(
            '%s library was not found. Looked in: %s',
            $label,
            implode(', ', $paths),
        ));
    }

    private static function resolveCandidate(string $candidate): ?string
    {
        if (file_exists($candidate)) {
            return $candidate;
        }

        if (is_dir($candidate)) {
            $library = self::libraryFileName($candidate);
            $path = rtrim($candidate, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $library;
            if (file_exists($path)) {
                return $path;
            }
        }

        return null;
    }

    private static function libraryFileName(string $candidate): string
    {
        if (stripos($candidate, 'ttf') !== false) {
            return 'libSDL3_ttf.dylib';
        }

        return 'libSDL3.dylib';
    }

    private static function fromPkgConfig(string $package): ?string
    {
        $value = trim((string) shell_exec(sprintf(
            'pkg-config --variable=libdir %s 2>/dev/null',
            escapeshellarg($package)
        )));

        if ($value === '') {
            return null;
        }

        $path = rtrim($value, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . self::libraryFileName($package);
        return file_exists($path) ? $path : null;
    }

    public static function sdl3Path(): string
    {
        $env = getenv('SDL3_LIBRARY_PATH');
        if (is_string($env) && $env !== '') {
            $resolved = self::resolveCandidate($env);
            if ($resolved !== null) {
                return $resolved;
            }
        }

        $pkgConfig = self::fromPkgConfig('sdl3');
        if ($pkgConfig !== null) {
            return $pkgConfig;
        }

        return self::find('SDL3', self::DEFAULT_SDL3_CANDIDATES);
    }

    public static function sdl3TtfPath(): string
    {
        $env = getenv('SDL3_TTF_LIBRARY_PATH');
        if (is_string($env) && $env !== '') {
            $resolved = self::resolveCandidate($env);
            if ($resolved !== null) {
                return $resolved;
            }
        }

        $pkgConfig = self::fromPkgConfig('sdl3-ttf');
        if ($pkgConfig !== null) {
            return $pkgConfig;
        }

        return self::find('SDL3_ttf', self::DEFAULT_SDL3_TTF_CANDIDATES);
    }
}
