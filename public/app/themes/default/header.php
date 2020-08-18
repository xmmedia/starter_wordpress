<!DOCTYPE html>
<html <?php language_attributes(); ?> prefix="og: https://ogp.me/ns#">
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

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
