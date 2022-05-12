<?php

declare(strict_types=1);

use Roots\WPConfig\Config;

class ThemeHelpers
{
    /** @var array */
    public static $manifestData;

    public static function assetPath(string $asset): string
    {
        if (null === self::$manifestData) {
            $manifestFile = dirname(ABSPATH).'/build/manifest.json';

            self::$manifestData = \json_decode(
                \file_get_contents($manifestFile),
                true
            );

            if (0 < \json_last_error()) {
                throw new \RuntimeException(
                    sprintf(
                        'Error parsing JSON from asset manifest file "%s" - %s',
                        $manifestFile,
                        json_last_error_msg()
                    )
                );
            }
        }

        if (array_key_exists($asset, self::$manifestData)) {
            return Config::get('WP_HOME').self::$manifestData[$asset];
        }

        return get_template_directory_uri().$asset;
    }
}
