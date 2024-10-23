<?php
/**  Template Name: Page */

get_header(); ?>

<main class="w-content flow-root">
    <p>Content</p>

    <?php
        if ( have_posts() ) {
            while (have_posts()) {
                the_post();
                the_content();
            }
        }
    ?>

    <svg class="w-5 h-5 mr-3 mb-0" width="20" height="20">
        <use xlink:href="<?php echo ThemeHelpers::iconsSvg(); ?>#icon"></use>
    </svg>
</main>

<?php get_footer();
