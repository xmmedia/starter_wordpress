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
        <header>
            <div class="flex flex-wrap justify-between items-center w-content"
                 :class="{ 'header-mobile-open': showMobileMenu }">
                <a href="/">
                    <!-- @todo-wordpress -->
                    <img src="<?php echo ThemeHelpers::assetPath('/images/logo.svg'); ?>" width="250" height="257" alt="Logo">
                </a>

                <nav class="w-full lg:w-auto">
                    <ul class="flex flex-no-wrap list-none">
                        <li class="header-nav_item">
                            <a href="/">About</a>
                        </li>
                        <li class="header-nav_item header-nav_item-products">
                            <a href="/">Products</a>
                        </li>
                        <li class="header-nav_item">
                            <a href="/">Contact</a>
                        </li>

                        <li v-if="!showMobileMenu" class="header-nav_item header-nav_item-toggle">
                            <button type="button"
                                    class="button-link"
                                    @click="toggleMobileMenu">+ More</button>
                        </li>
                        <li v-else class="header-nav_item header-nav_item-toggle">
                            <button type="button"
                                    class="button-link"
                                    @click="toggleMobileMenu">– Less</button>
                        </li>
                    </ul>
                </nav>
            </div>
        </header>
