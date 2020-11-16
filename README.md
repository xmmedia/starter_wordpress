# XM WordPress Starter

* Required: composer, node, yarn
* Optional: wp-cli
* Based on https://github.com/roots/bedrock

## Initial Setup

1. Create a new project:
    ```sh
    composer create-project xm/starter_wordpress project-name --stability=dev --no-install --remove-vcs
    ```
2. Copy `.env.example` to `.env`.
6. Update environment variables in the `.env` file:
  * Database: define `DATABASE_URL` for using a DSN (e.g. `mysql://user:password@127.0.0.1:3306/db_name`)
  * `WP_ENV` - Set to environment (`development`, `staging`, `production`)
  * `WP_HOME` - Full URL to WordPress home (https://dev.example.com)
  * `WP_SITEURL` - Full URL to WordPress including subdirectory (https://example.com/wp)
  * `WPCOM_API_KEY` - Wordpress.com API key for Akismet and Jetpack (or other WordPress paid plugins)
  * `AUTH_KEY`, `SECURE_AUTH_KEY`, `LOGGED_IN_KEY`, `NONCE_KEY`, `AUTH_SALT`, `SECURE_AUTH_SALT`, `LOGGED_IN_SALT`, `NONCE_SALT` from: https://roots.io/salts.html
  * `ACF_PRO_KEY` - add ACF key
7. Server setup:
    1. If using InterWorx or CentOS, upload `setup_dev.sh` and run: `sh ./setup_dev.sh`
    2. Upload files to the server. Don't upload (most are listed in `.gitignore`):
        - `/.git` and `/.idea`
        - Plus the following only if you JS or PHP packages have been installed:
          - `/public/wp`
          - `/public/app/plugins/*`
          - `/vendor`
          - `/bin`
          - `/node_modules`
    3. [Install Composer](https://getcomposer.org/download/) and then install PHP packages on server: `php composer.phar install`
    4. [Install NVM](https://github.com/creationix/nvm#install-script). You may need `. ~/bashrc` or `. ~/.zshrc` for nvm to be enabled.
    5. Run `. ./node_setup.sh` (this will setup node & install the JS packages – requires yarn to be installed).
    6. Run `yarn dev` or `yarn build` (for production) to compile JS & CSS files.
    7. Link public to html: `rm -rf html && ln -s public html`
    8. Create a symlink between vendor and plugin directory for ACF: `ln -s /home/<user>/dev.example.com/vendor/advanced-custom-fields/advanced-custom-fields-pro public/app/plugins/acf`
    9. Add cron: `*/15 * * * * curl https://dev.example.com/wp/wp-cron.php` (this is every 15 minutes). The automatic cron is disabled.
    10. Adjust permissions on the bin dir: `chmod u+x bin/*`
    11. Install WP: `bin/wp core install --allow-root --url=https://<url> --title="<site-title>" --admin_user=<username> --admin_email=<email>`
4. Update `composer.json`: `name`, `license` (likely `private` & uncomment `private`) and `description`
5. Update `package.json`: `name`, `version`, `git.url`, `license`, `private`, `script.dev-server`
8. Install PHP packages & update locally: `composer install && composer update`
9. Run `yarn && yarn upgrade` locally.
10. Upload `composer.lock` and `yarn.lock` and on the server, re-run `php composer.phar install` and `. ./node_setup.sh` again.
11. Find and make changes near `@todo-wordpress` comments throughout the site. All changed files will need to uploaded to the server.
12. Access WordPress admin at `https://dev.example.com/wp/wp-admin/`
13. To activate all installed plugins: `bin/wp plugin activate --all`
14. Delete or update `README.md` and `LICENSE`
15. Add the Postmark API key
16. Create new favicons: [realfavicongenerator.net](https://realfavicongenerator.net)
17. Consider the following WordPress settings:
    - Setting the homepage: create the page and then select the home page under Settings > Reading 
    - Dev: Settings > Reading "Discourage search engines from indexing this site" (for Dev)

## System Requirements

  - PHP 7.3+
  - MySQL 5.7+
  - [Composer](https://getcomposer.org/download/)
  - [Yarn](https://yarnpkg.com/en/docs/install)

## Adding Plugins/Themes using WPackagist

For packages that are found on [WPackagist](https://wpackagist.org/) and support Composer install.

1. Run `composer require wpackagist-plugin/plugin-name` or `composer require wpackagist-theme/theme-name`
2. Upload the `composer.json` & `composer.lock` files to the server (if applicable).
3. Run `php composer.phar install` on the server (if applicable).
4. Activate and configure the plugin or theme in WordPress.

Note: for plugins installed this way, only the references to plugin 
in `composer.json` and `composer.lock` are committed to git.
The actual plugin files are not committed to git.

## Adding Plugins/Themes *not on* WPackagist

Download the archive of the plugin and put in the `/public/app/mu-plugins/<plugin-name>/` dir.
Add a line to `.gitignore` such as `!public/app/mu-plugins/plugin/` so the plugin is detected/include by git.
Commit the plugin to git. It will need to manually updated.

WordPress only looks for PHP files right inside the mu-plugins directory, and (unlike for normal plugins) not for files in subdirectories. You may need to create a proxy PHP loader file inside the mu-plugins directory (i.e. load.php). Only do this if it is not finding the plugin file on it's own, otherwise you'll get a duplication error.

`<?php require WPMU_PLUGIN_DIR.'/my-plugin/my-plugin.php';`

## Referencing Assets

To reference assets (images, CSS files, etc) that are located in the theme, use the following function:

`<?php echo ThemeHelpers::assetPath('/path/within/theme/dir.jpg'); ?>`

This will use WordPress' internal path and URL generation to deal with the theme folder changing or moving. The slash at the beginning of the path is optional.

changing theme name

## Updating WordPress & Plugins/Packages

To update WordPress core and all the plugins, run the following locally.
Replace `5.2.3` with the current version. 

`composer update && composer require roots/wordpress:5.2.3`

The latest version of WordPress available can be found here: https://github.com/roots/wordpress/releases

## Changing the Theme Name/Path

To change the theme name from the default `default` to, for example, `company`:

1. Rename the theme folder.
2. In `config/application.php`, update the `Config::define('WP_DEFAULT_THEME', 'default');` line.
3. In `webpack.base.config.js`, update all the paths referencing the theme folder, ie, `public/app/themes/default/...`

## Commands

  - Production JS/CSS build: `yarn build`
  - Dev JS/CSS build: `yarn dev`
  - Dev JS/CSS watch: `yarn watch` (files will not be versioned)
  - Dev JS/CSS HMR server: `yarn dev-server` (if configured)
  - JS Tests ([Jest](https://jestjs.io/)): `yarn test:unit` (if configured)
  - E2E Tests ([Cypress](https://www.cypress.io/)): `yarn test:e2e` (if configured)
  - Linting:
    - JS ([ESLint](https://eslint.org/)): `yarn lint:js` or `yarn lint:js:fix`
    - CSS: `yarn lint:css` or `yarn lint:css:fix`

## Going Live / After Launch

  - Ensure "From" / "To" addresses in Contact Forms are correct
  - Remove Test Blog Post and Sample Page or Google will index these.
  - Ensure "Discourage search engines from indexing this site" is unchecked under Settings > Reading
  - If not using "Posts" turn off Post indexing in Yoast > Search Appearance > Content
  - If not using "Posts" turn off Author indexing in Yoast > Search Appearance > Archives - otherwise Google will index a page for each user/author, whether it is being used or not.
