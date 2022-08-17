<?php

// Load value.
$iframe = get_field('oembed');

// Use preg_match to find iframe src.
preg_match('/width="(.+?)"/', $iframe, $matches);
$width = $matches[1];

preg_match('/height="(.+?)"/', $iframe, $matches);
$height = $matches[1];

$ratio = round($width / $height, 3);

$availableRatios = [
    'embed-container-21-9' => 2.333,
    'embed-container-18-9' => 2,
    'embed-container-16-9' => 1.778,
    'embed-container-4-3'  => 1.333,
];

// determine the ratio that's closest
// this may not be exact for custom sized videos
$closestRatios = null;
foreach ($availableRatios as $ratioClass => $possibleRatio) {
    if ($closestRatios === null || abs($ratio - $closestRatios) > abs($possibleRatio - $ratio)) {
        $closestRatios = $possibleRatio;
    }
}

?>

<div class="bg-black border-t-8 border-gray-800">
    <?php /* embed-wrap needed for responsive width control */ ?>
    <div class="embed-wrap">
        <div class="embed-container <?php echo esc_attr(array_search($closestRatios, $availableRatios)); ?>">
            <?php the_field('oembed'); ?>
        </div>
    </div>
</div>
