<?php

declare(strict_types=1);

use Roots\WPConfig\Config;

class ThemeHelpers
{
    public static array $entryPoints;

    public static function assetPath(string $asset, string $type): ?string
    {
        if (!isset(self::$entryPoints)) {
            $manifestFile = dirname(ABSPATH).'/build/.vite/entrypoints.json';

            try {
                self::$entryPoints = \json_decode(
                    \file_get_contents( $manifestFile ),
                    true,
                    512,
                    JSON_THROW_ON_ERROR,
                );
            } catch (\JsonException $e) {
                throw new \JsonException(sprintf(
                    'Error parsing JSON from asset manifest file "%s" - %s',
                    $manifestFile,
                    $e->getMessage(),
                ), 0, $e);
            }
        }

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

            return rtrim(Config::get('WP_HOME'), '/').$assetPath;
        }


        return self::asset($asset);
    }

    public static function asset(string $asset): string
    {
        // deal with trailing and leading slashes does it doesn't matter what's passed
        return sprintf('%s/%s', rtrim(get_template_directory_uri(), '/'), ltrim($asset, '/'));
    }
}
