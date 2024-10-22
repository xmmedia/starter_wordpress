<!DOCTYPE html>
<html <?php language_attributes(); ?> prefix="og: https://ogp.me/ns#">
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <?php wp_head(); ?>

    <?php // @todo-wordpress add favicon tags here ?>

    <?php if (Env\env('GA_ANALYTICS_ID')) : ?>
        <!-- Google tag (gtag.js) -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo Env\env('GA_ANALYTICS_ID'); ?>"></script>
        <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', '<?php echo Env\env('GA_ANALYTICS_ID'); ?>');
        </script>
    <?php endif; ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

    <div id="app">
        <header>
            <div class="flex flex-wrap justify-between items-center w-content"
                 :class="{ 'header-mobile-open': showMobileMenu }">
                <a href="/">
                    <!-- @todo-wordpress -->
                    <img src="<?php echo ThemeHelpers::asset('/images/logo.svg'); ?>" width="250" height="257" alt="Logo">
                </a>

                <div id="menu"></div>
            </div>
        </header>
