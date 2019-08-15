<?php

declare(strict_types=1);

class ThemeHelpers
{
    public static function assetPath(string $asset): string
    {
        $manifestFile = get_template_directory_uri().'/build/manifest.json';
        $manifestData = \json_decode(\file_get_contents($manifestFile), true);
        if (0 < \json_last_error()) {
            throw new \RuntimeException(
                sprintf(
                    'Error parsing JSON from asset manifest file "%s" - %s',
                    $manifestFile,
                    json_last_error_msg()
                )
            );
        }

        $path = isset($manifestData[$asset]) ? $manifestData[$asset] : $asset;

        return get_template_directory_uri().$path;
    }
}
