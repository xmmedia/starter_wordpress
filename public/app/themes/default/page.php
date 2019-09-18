<?php
/**  Template Name: Page */

get_header(); ?>

<main class="max-w-11/12 max:max-w-6xl mx-auto clearfix">
    <p>Content</p>

    <?php
        if ( have_posts() ) {
            while (have_posts()) {
                the_post();
                the_content();
            }
        }
    ?>
</main>

<?php get_footer();
