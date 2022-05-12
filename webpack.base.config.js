'use strict';
const path = require('path');
const BundleAnalyzerPlugin = require('webpack-bundle-analyzer').BundleAnalyzerPlugin;

function resolve (dir) {
    return path.join(__dirname, '.', dir);
}

// Base configuration of Encore/Webpack
module.exports = function (Encore) {
    // Manually configure the runtime environment if not already configured yet by the "encore" command.
    // It's useful when you use tools that rely on webpack.config.js file.
    if (!Encore.isRuntimeEnvironmentConfigured()) {
        Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
    }

    Encore
        // directory where all compiled assets will be stored
        .setOutputPath('public/build/')

        // what's the public path to this directory (relative to your project's document root dir)
        .setPublicPath('/build')

        // always create hashed filenames (e.g. public.a1b2c3.css)
        .enableVersioning(!Encore.isDevServer())

        // empty the outputPath dir before each build
        .cleanupOutputBeforeBuild()

        // don't output the runtime chunk as we only include 1 JS file per page
        .disableSingleRuntimeChunk()

        // will output as build/public.js and similar
        .addEntry('public', './public/app/themes/default/js/src/public.js')
        .addEntry('blocks', './public/app/themes/default/js/src/blocks.js')

        // adds integrity="..." attributes on your script & link tags
        // requires WebpackEncoreBundle 1.4 or higher
        .enableIntegrityHashes(Encore.isProduction())

        // allow sass/scss files to be processed
        .enableSassLoader(function (options) {
            options.additionalData = "$env: " + process.env.NODE_ENV + ";";
        }, {
            // see: http://symfony.com/doc/current/frontend/encore/bootstrap.html#importing-bootstrap-sass
            resolveUrlLoader: false,
        })
        .enablePostCssLoader()
        // allow .vue files to be processed
        .enableVueLoader((options) => {
            options.transpileOptions = {
                transforms: {
                    // required to use gql within template tags
                    // (such as with the ApolloQuery component)
                    dangerousTaggedTemplateString: true,
                },
            };
        }, { runtimeCompilerBuild: true })

        .enableSourceMaps(Encore.isDev() || Encore.isDevServer())

        .configureBabel(null, {
            includeNodeModules: [
                'vue-apollo', // Object.entries()
            ],
        })

        .addLoader({
            test: /\.svg$/,
            use: [
                {
                    loader: 'svgo-loader',
                },
            ],
        })

        .addAliases({
            '@': resolve('public/app/themes/default/js/src'),
            'vue$': 'vue/dist/vue.esm.js',
        })

        .autoProvidejQuery()

        /* eslint-disable no-unused-vars */
        .configureDefinePlugin((options) => {
            const env = require('dotenv').config({ path: '.env' });

            // options['process.env.VERSION'] = JSON.stringify('1.2.3');
            // options['process.env.WP_ENV'] = JSON.stringify(env.parsed.WP_ENV);
        })
        /* eslint-enable no-unused-vars */
    ;

    if (Encore.isProduction()) {
        Encore
            .addPlugin(new BundleAnalyzerPlugin({
                analyzerMode: 'static',
                openAnalyzer: false,
            }))
        ;
    }

    if (Encore.isDev()) {
        Encore
            .copyFiles({
                from: './node_modules/svgxuse',
                to: '[name].[hash:8].[ext]',
                pattern: /\.js$/,
            })
        ;
    }
};
