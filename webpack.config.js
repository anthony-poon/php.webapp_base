var Encore = require('@symfony/webpack-encore');
const { VueLoaderPlugin } = require('vue-loader');

Encore
    // the project directory where compiled assets will be stored
    .setOutputPath('public/build/')
    // the public path used by the web server to access the previous directory
    .setPublicPath('/build')
    .cleanupOutputBeforeBuild()
    .addStyleEntry("base/login", "./assets/scss/security/login.scss")
    .addEntry("base/base", ["./assets/js/base.js", "./assets/scss/base/base.scss"])
    .addEntry("base/data_table_test", [
        "./assets/js/share/vue_component/RestTable.vue",
        "./assets/js/data_table_test.js"
    ])
    .addEntry("base/list_user", [
        "./assets/js/share/vue_component/RestTable.vue",
        "./assets/js/list_user.js",
        "./assets/scss/admin/list_user.scss"
    ])
    .enableVueLoader()
    .addPlugin(new VueLoaderPlugin())
    .createSharedEntry('vendor', [
        'jquery',
        'bootstrap',
        'bootstrap/scss/bootstrap.scss'
    ])
    .enableSourceMaps(!Encore.isProduction())
    .enableReactPreset()
    .enableSassLoader()
    .enableVueLoader()
    .autoProvidejQuery()
    // uncomment to create hashed filenames (e.g. app.abc123.css)
    // .enableVersioning(Encore.isProduction())

    // uncomment to define the assets of the project
    // .addEntry('js/app', './assets/js/app.js')
    // .addStyleEntry('css/app', './assets/css/app.scss')
;

module.exports = Encore.getWebpackConfig();
