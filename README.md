# XM WordPress Starter

* Required: composer, node, yarn
* Optional: wp-cli
* Based on https://github.com/roots/bedrock

1. Create a new project:
    ```sh
    composer create-project xm/starter_wordpress project-name  --stability=dev
    ```
2. Copy `.env.example` to `.env` then update environment variables in the `.env` file:
  * Database: define `DATABASE_URL` for using a DSN (e.g. `mysql://user:password@127.0.0.1:3306/db_name`)
  * `WP_ENV` - Set to environment (`development`, `staging`, `production`)
  * `WP_HOME` - Full URL to WordPress home (https://dev.example.com)
  * `WP_SITEURL` - Full URL to WordPress including subdirectory (https://example.com/wp)
  * `AUTH_KEY`, `SECURE_AUTH_KEY`, `LOGGED_IN_KEY`, `NONCE_KEY`, `AUTH_SALT`, `SECURE_AUTH_SALT`, `LOGGED_IN_SALT`, `NONCE_SALT` from: https://roots.io/salts.html
5. Access WordPress admin at `https://dev.example.com/wp/wp-admin/`
5. Create a symlink between vendor and plugin directory for ACF: `ln -s /home/<user>/dev.example.com/vendor/advanced-custom-fields/advanced-custom-fields-pro public/app/plugins/acf`
6. Add cron: `*/15 * * * * curl https://dev.example.com/wp/wp-cron.php` (this is every 15 minutes)

## Adding Plugins/Themes using WPackagist

For packages that are found on [WPackagist](https://wpackagist.org/) and support Composer install.

1. Run `composer require wpackagist-plugin/plugin-name` or `composer require wpackagist-theme/theme-name`
2. Upload the `composer.json` & `composer.lock` files to the server (if applicable).
3. Run `php composer.phar install` on the server.
4. Activate the plugin or theme in WordPress.

## Updating Packages

- add Akismet key
- add Postmark api key
- add ACF key
