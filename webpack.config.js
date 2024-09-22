const Encore = require('@symfony/webpack-encore');

// Manually configure the runtime environment if not already configured yet by the "encore" command.
// It's useful when you use tools that rely on webpack.config.js file.
if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    // directory where compiled assets will be stored
    .setOutputPath('public/build/')
    // public path used by the web server to access the output path
    .setPublicPath('/build')
    // only needed for CDN's or subdirectory deploy
    //.setManifestKeyPrefix('build/')

    /*
     * Copy Klassy Cafe theme pictures
     */
    .copyFiles({
        from: './assets/klassy_cafe/assets/images',

        // optional target path, relative to the output dir
        to: 'image/klassy-cafe/[path][name].[ext]',

        // if versioning is enabled, add the file hash too
        //to: 'images/[path][name].[hash:8].[ext]',

        // only copy pictures
        pattern: /\.(png|jpg|jpeg|svg)$/
    })
    // copy project pics in the build directory
    .copyFiles({
        from: './assets/images',
        to: 'image/[path][name].[ext]',
        pattern: /\.(png|jpg|jpeg|svg)$/
    })

    /*
     * ENTRY CONFIG
     *
     * Each entry will result in one JavaScript file (e.g. app.js)
     * and one CSS file (e.g. app.css) if your JavaScript imports CSS.
     */
    .addEntry('app', './assets/app.js')
    .addEntry('global', './assets/javascript/global.js')
    .addEntry('recipe_form', './assets/javascript/recipe/_form.js')
    .addEntry('recipe_toggle_favorite', './assets/javascript/recipe/_toggle_favorite.js')
    .addEntry('recipe_nutritional_values_ingredients', './assets/javascript/recipe/_nutritional_values_ingredients.js')
    .addEntry('recipe_show', './assets/javascript/recipe/show.js')
    .addEntry('recipe_index', './assets/javascript/recipe/index.js')
    .addEntry('recipe_card', './assets/javascript/recipe/_recipe_card.js')
    .addEntry('meal_list_form', './assets/javascript/meal_list/_form.js')
    .addEntry('meal_list_card', './assets/javascript/meal_list/_meal_list_card.js')
    .addEntry('my_account_notifications', './assets/javascript/my_account/notifications.js')
    .addEntry('my_account_follow_propositions', './assets/javascript/my_account/follow_propositions.js')
    .addEntry('ingredient_form', './assets/javascript/ingredient/_form.js')
    .addEntry('ingredient_show', './assets/javascript/ingredient/show.js')

    // enables the Symfony UX Stimulus bridge (used in assets/bootstrap.js)
    .enableStimulusBridge('./assets/controllers.json')

    // When enabled, Webpack "splits" your files into smaller pieces for greater optimization.
    .splitEntryChunks()

    // will require an extra script tag for runtime.js
    // but, you probably want this, unless you're building a single-page app
    .enableSingleRuntimeChunk()

    /*
     * FEATURE CONFIG
     *
     * Enable & configure other features below. For a full
     * list of features, see:
     * https://symfony.com/doc/current/frontend.html#adding-more-features
     */
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    // enables hashed filenames (e.g. app.abc123.css)
    .enableVersioning(Encore.isProduction())

    // configure Babel
    // .configureBabel((config) => {
    //     config.plugins.push('@babel/a-babel-plugin');
    // })

    // enables and configure @babel/preset-env polyfills
    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = 'usage';
        config.corejs = '3.23';
    })

    // enables Sass/SCSS support
    .enableSassLoader()

    // uncomment if you use TypeScript
    //.enableTypeScriptLoader()

    // uncomment if you use React
    //.enableReactPreset()

    // uncomment to get integrity="..." attributes on your script & link tags
    // requires WebpackEncoreBundle 1.4 or higher
    //.enableIntegrityHashes(Encore.isProduction())

    // uncomment if you're having problems with a jQuery plugin
    // .autoProvidejQuery()
;

module.exports = Encore.getWebpackConfig();
