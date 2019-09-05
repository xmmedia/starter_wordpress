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
3. Add the ACF key to the `.env` or remove from composer.json.
4. Update `composer.json`: `name`, `license` (likely `private`) and `description`
5. Update `package.json`: `name`, `version`, `git.url`, `license`, `private`, `script.dev-server`
6. Install PHP packages & update: `composer install && composer update`
7. Run `yarn && yarn upgrade` locally.
8. Update environment variables in the `.env` file:
  * Database: define `DATABASE_URL` for using a DSN (e.g. `mysql://user:password@127.0.0.1:3306/db_name`)
  * `WP_ENV` - Set to environment (`development`, `staging`, `production`)
  * `WP_HOME` - Full URL to WordPress home (https://dev.example.com)
  * `WP_SITEURL` - Full URL to WordPress including subdirectory (https://example.com/wp)
  * `AUTH_KEY`, `SECURE_AUTH_KEY`, `LOGGED_IN_KEY`, `NONCE_KEY`, `AUTH_SALT`, `SECURE_AUTH_SALT`, `LOGGED_IN_SALT`, `NONCE_SALT` from: https://roots.io/salts.html
9. Find and make changes near `@todo-wordpress` comments throughout the site.
10. Server setup:
    1. Upload files to the server. Don't upload (most are listed in `.gitignore`):
        - `/public/wp`
        - `/public/app/plugins/*`
        - `/vendor`
        - `/bin`
        - `/.git or /.idea`
        - `/node_modules`
    2. [Install Composer](https://getcomposer.org/download/) and then install PHP packages on server: `php composer.phar install`
    3. [Install NVM](https://github.com/creationix/nvm#install-script)
    4. Run `. ./node_setup.sh` (this will setup node & install the JS packages).
    5. Run `yarn dev` or `yarn build` (for production) to compile JS & CSS files.
    6 link public to html: `rm -rf html && ln -s public html`
    7. Create a symlink between vendor and plugin directory for ACF: `ln -s /home/<user>/dev.example.com/vendor/advanced-custom-fields/advanced-custom-fields-pro public/app/plugins/acf`
    8. Add cron: `*/15 * * * * curl https://dev.example.com/wp/wp-cron.php` (this is every 15 minutes). The automatic cron is disabled.
    9. Adjust permissions on the bin dir: `chmod u+x bin/*`
11. Access WordPress admin at `https://dev.example.com/wp/wp-admin/`
12. Delete or update `README.md`
13. Create new favicons: [realfavicongenerator.net](https://realfavicongenerator.net)

## System Requirements

  - PHP 7.3+
  - MySQL 5.7+
  - [Yarn](https://yarnpkg.com/en/docs/install)

## Adding Plugins/Themes using WPackagist

For packages that are found on [WPackagist](https://wpackagist.org/) and support Composer install.

1. Run `composer require wpackagist-plugin/plugin-name` or `composer require wpackagist-theme/theme-name`
2. Upload the `composer.json` & `composer.lock` files to the server (if applicable).
3. Run `php composer.phar install` on the server.
4. Activate and configure the plugin or theme in WordPress.

## Adding Plugins/Themes *not on* WPackagist

Download the archive of the plugin and put in the `/public/app/mu-plugins/<plugin-name>/` dir.
Include this in the git repo. It will need to manually updated.

## Updating Packages

- add Akismet key
- add Postmark api key
- add ACF key

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
