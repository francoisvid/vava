var Encore = require('@symfony/webpack-encore');

Encore
// directory where compiled assets will be stored
    .setOutputPath('public/build/')
    // public path used by the web server to access the output path
    .setPublicPath('/build')
    // only needed for CDN's or sub-directory deploy
    //.setManifestKeyPrefix('build/')

    /*
     * ENTRY CONFIG
     *
     * Add 1 entry for each "page" of your app
     * (including one that's included on every page - e.g. "app")
     *
     * Each entry will result in one JavaScript file (e.g. app.js)
     * and one CSS file (e.g. app.css) if you JavaScript imports CSS.
     */
    //CSS
    //    .addStyleEntry('appCSS', './assets/css/app.css')
    //    .addStyleEntry('semantic', './assets/css/semantic.min.css')

    //JavaScript
    //    .addEntry('appJS', './assets/js/app.js')
    //    .addEntry('semanticJS', './assets/js/semantic.min.js')

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

    // enables Sass/SCSS support
    //.enableSassLoader()

    .addEntry('js/admin', './assets/js/admin.js')
    .addEntry('json/DT_French', './assets/DataTables/DT_French.json')
    .addEntry('js/user', './assets/js/user.js')
    .addEntry('js/form', './assets/js/form.js')
    .addEntry('img', './assets/js/recursifEncore.js')
    .addEntry('sem', './assets/js/app.js')
    // .addEntry('js/base', './assets/js/base.js')
    // .addEntry('datatablejs', './assets/DataTables/datatables.js')
    .addStyleEntry('css/user', './assets/css/user.css')
    .addStyleEntry('css/base', './assets/css/base.css')
    .addStyleEntry('css/style', './assets/css/style.css')
    .addStyleEntry('css/entrepriseform', './assets/css/entrepriseform.css')
    .addStyleEntry('css/map', './assets/css/map.css')
    .addStyleEntry('css/bootstrap', './assets/css/bootstrap.css')
    // .addStyleEntry('datatablecss', './assets/DataTables/datatables.css')
    // uncomment if you use TypeScript
    //.enableTypeScriptLoader()

    // uncomment if you're having problems with a jQuery plugin
    .autoProvidejQuery()


    .configureFilenames({
        images: 'image/[name].[ext]'

    });
module.exports = Encore.getWebpackConfig();