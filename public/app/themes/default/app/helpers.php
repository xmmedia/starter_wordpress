<?php

declare(strict_types=1);

use Roots\WPConfig\Config;

class ThemeHelpers
{
    public static array $entryPoints;
    public static array $manifest;


    public static function assetPath(string $asset, ?string $type = null): ?string
    {
        self::load();

        if (array_key_exists($asset, self::$entryPoints['entryPoints'])) {
            if (!array_key_exists($type, self::$entryPoints['entryPoints'][$asset])) {
                return null;
            }

            $assetPath = self::$entryPoints['entryPoints'][$asset][$type][0];

            // support absolute URLs in the manifest
            // helpful when running the webpack dev server
            if (str_starts_with($assetPath, 'http')) {
                return $assetPath;
            }

            return get_template_directory_uri().$assetPath;
        }

        if (isset(self::$manifest) && array_key_exists($asset, self::$manifest)) {
            $assetPath = self::$manifest[$asset]['file'];

            // support absolute URLs in the manifest
            // helpful when running the webpack dev server
            if (str_starts_with($assetPath, 'http')) {
                return $assetPath;
            }

            return '/build/'.$assetPath;
        }

        return self::asset($asset);
    }

    public static function iconsSvg(): string
    {
        self::load();

        if (null !== self::$entryPoints['viteServer']) {
            return sprintf('%s/build/public/app/themes/default/images/icons.svg', self::$entryPoints['viteServer']);
        }

        return self::assetPath('public/app/themes/default/images/icons.svg');
    }

    public static function asset(string $asset): string
    {
        // deal with trailing and leading slashes does it doesn't matter what's passed
        return sprintf('%s/%s', rtrim(get_template_directory_uri(), '/'), ltrim($asset, '/'));
    }

    private static function load(): void
    {
        if (!isset(self::$entryPoints)) {
            $entryPointsFile = dirname(ABSPATH).'/build/.vite/entrypoints.json';
            $manifestFile = dirname(ABSPATH).'/build/.vite/manifest.json';
            
            try {
                self::$entryPoints = \json_decode(
                    \file_get_contents($entryPointsFile),
                    true,
                    512,
                    JSON_THROW_ON_ERROR,
                );
                if (file_exists($manifestFile)) {
                    self::$manifest = \json_decode(
                        \file_get_contents($manifestFile),
                        true,
                        512,
                        JSON_THROW_ON_ERROR,
                    );
                }
            } catch (\JsonException $e) {
                throw new \JsonException(sprintf(
                    'Error parsing JSON from asset entrypoints ("%s") or manifest ("%s"): %s',
                    $entryPointsFile,
                    $manifestFile,
                    $e->getMessage(),
                ), 0, $e);
            }
        }
    }
}
