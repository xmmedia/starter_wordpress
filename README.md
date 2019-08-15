# XM WordPress Starter

* Required: composer, node, yarn, wp-cli
* Base on https://github.com/roots/bedrock

1. Create a new project:
    ```sh
    composer create-project xm/starter_wordpress
    ```
2. Update environment variables in the `.env` file:
  * Database: define `DATABASE_URL` for using a DSN (e.g. `mysql://user:password@127.0.0.1:3306/db_name`)
  * `WP_ENV` - Set to environment (`development`, `staging`, `production`)
  * `WP_HOME` - Full URL to WordPress home (https://example.com)
  * `WP_SITEURL` - Full URL to WordPress including subdirectory (https://example.com/wp)
  * `AUTH_KEY`, `SECURE_AUTH_KEY`, `LOGGED_IN_KEY`, `NONCE_KEY`, `AUTH_SALT`, `SECURE_AUTH_SALT`, `LOGGED_IN_SALT`, `NONCE_SALT` from: https://roots.io/salts.html
5. Access WordPress admin at `https://dev.example.com/wp/wp-admin/`
