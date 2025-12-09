<!DOCTYPE html>
<html <?php language_attributes(); ?> prefix="og: https://ogp.me/ns#">
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <?php wp_head(); ?>

    <?php // @todo-wordpress update favicon tags here ?>
    <link rel="icon" type="image/png" href="/favicon-96x96.png" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="/favicon.svg" />
    <link rel="shortcut icon" href="/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png" />
    <meta name="apple-mobile-web-app-title" content="XM Media" />

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

        <header>
            <div class="flex flex-wrap justify-between items-center w-content">
                <a href="/">
                    <!-- @todo-wordpress -->
                    <img src="<?php echo ThemeHelpers::asset('/images/logo.svg'); ?>" width="250" height="257" alt="Logo">
                </a>

                <div id="menu"></div>
            </div>
        </header>
