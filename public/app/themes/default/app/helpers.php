<?php

declare(strict_types=1);

use Roots\WPConfig\Config;

class ThemeHelpers
{
    public static array $manifestData;

    public static function assetPath(string $asset): string
    {
        if (!isset(self::$manifestData)) {
            $manifestFile = dirname(ABSPATH).'/build/manifest.json';

            try {
                self::$manifestData = \json_decode(
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

        if (array_key_exists($asset, self::$manifestData)) {
            // support absolute URLs in the manifest
            // helpful when running the webpack dev server
            if (str_starts_with(self::$manifestData[$asset], 'http')) {
                return self::$manifestData[$asset];
            }

            return rtrim(Config::get('WP_HOME'), '/').self::$manifestData[$asset];
        }

        // deal with trailing and leading slashes does it doesn't matter what's passed
        return sprintf('%s/%s', rtrim(get_template_directory_uri(), '/'), ltrim($asset, '/'));
    }
}
