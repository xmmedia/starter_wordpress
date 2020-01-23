'use strict';
const path = require('path');
const Dotenv = require('dotenv-webpack');
const BundleAnalyzerPlugin = require('webpack-bundle-analyzer').BundleAnalyzerPlugin;

function resolve (dir) {
    return path.join(__dirname, '.', dir);
}

// Base configuration of Encore/Webpack
module.exports = function (Encore) {
    if (!Encore.isRuntimeEnvironmentConfigured()) {
        Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
    }

    Encore
    // directory where all compiled assets will be stored
        .setOutputPath('public/app/themes/default/build/')

        // what's the public path to this directory (relative to your project's document root dir)
        .setPublicPath('/build')

        .setManifestKeyPrefix('')

        // always create hashed filenames (e.g. public.a1b2c3.css)
        .enableVersioning(true)

        // empty the outputPath dir before each build
        .cleanupOutputBeforeBuild()

        // don't output the runtime chunk as we only include 1 JS file per page
        .disableSingleRuntimeChunk()

        // will output as build/public.js and similar
        .addEntry('public', './public/app/themes/default/js/src/public.js')
        .addEntry('blocks', './public/app/themes/default/js/src/blocks.js')
        .addEntry('admin', './public/app/themes/default/js/src/admin.js')

        // allow sass/scss files to be processed
        .enableSassLoader(function(sassOptions) {}, {
            // see: http://symfony.com/doc/current/frontend/encore/bootstrap.html#importing-bootstrap-sass
            resolveUrlLoader: false,
        })
        .enablePostCssLoader()
        // allow .vue files to be processed
        .enableVueLoader((options) => {
            options.transpileOptions = {
                transforms: {
                    dangerousTaggedTemplateString: true
                },
            };
        })

        .enableSourceMaps(true)

        .configureBabel(() => {}, {
            includeNodeModules: [
                'vue-apollo', // Object.entries()
            ],
        })

        .addLoader({
            test: /\.svg$/,
            use: [
                {
                    loader: 'svgo-loader',
                    options: {
                        plugins: [
                            // config targeted at icon files, but should work for others
                            { removeUselessDefs: false },
                            { cleanupIDs: false },
                        ],
                    },
                },
            ],
        })

        .addAliases({
            '@': resolve('js/src'),
            'vue$': 'vue/dist/vue.esm.js',
        })
        .autoProvidejQuery()

        .addPlugin(new Dotenv())

        .addPlugin(new BundleAnalyzerPlugin({
            analyzerMode: 'static',
            openAnalyzer: false,
        }))
    ;

    if (Encore.isProduction()) {
        Encore
    }
};
