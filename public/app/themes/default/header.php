<!DOCTYPE html>
<html <?php language_attributes(); ?> prefix="og: https://ogp.me/ns#">
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

    <!--[if lte IE 9]>
    <div class="bg-red-500">
        <div class="max-w-lg my-0 mx-auto p-3 text-white text-center">
            <strong class="text-xl">This site does not support the browser you are using.</strong><br>
            We recommend upgrading your browser as it's out-of-date.<br>
            Please check <a href="https://browsehappy.com/" class="text-inherit hover:text-grey-600">these instructions regarding updating your browser</a>.<br>
            We only support evergreen browsers, such as Chrome, Safari, Edge, and Firefox.
        </div>
    </div>
    <![endif]-->

    <div id="app">
        <header class="w-content">
            <!-- @todo-wordpress -->
            Company Name
            <img src="<?php echo ThemeHelpers::assetPath('/images/logo.svg'); ?>" width="250" height="257" alt="Logo">

            <nav>
                <ul class="">
                    <li class="header-nav_item"><a href="/">Home</a></li>
                </ul>
            </nav>
        </header>
