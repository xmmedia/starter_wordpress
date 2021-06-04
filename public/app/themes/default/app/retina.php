<?php

declare(strict_types=1);

/**
 * Add retina versions of all image sizes.
 */
foreach (wp_get_registered_image_subsizes() as $name => $dimensions) {
    add_image_size(
        $name.'_retina',
        $dimensions['width'] * 2,
        $dimensions['height'] * 2,
    );
}
add_filter(
    'image_downsize',
    function ($value, $id, $size) {
        if (strrpos($size, '_retina') > 0) {
            if (!is_array($imageData = wp_get_attachment_metadata($id))) {
                return false;
            }

            $regularSize = substr($size, 0, strpos($size, '_retina'));

            $imgUrl = wp_get_attachment_url($id);
            $imgUrlBasename = wp_basename($imgUrl);

            if (isset($imageData['sizes'][$regularSize])) {
                $width = $imageData['sizes'][$regularSize]['width'];
                $height = $imageData['sizes'][$regularSize]['height'];

                if (isset($imageData['sizes'][$size])) {
                    $url = str_replace(
                        $imgUrlBasename,
                        $imageData['sizes'][$size]['file'],
                        $imgUrl,
                    );
                } else {
                    $url = $imgUrl;
                }

            } else {
                $url = $imgUrl;
                $width = $imageData['width'];
                $height = $imageData['height'];
            }

            return [$url, $width, $height];
        }

        return $value;
    },
    10,
    3,
);
