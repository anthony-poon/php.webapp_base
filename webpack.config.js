var Encore = require('@symfony/webpack-encore');

Encore
    // the project directory where compiled assets will be stored
    .setOutputPath('public/build/')
    // the public path used by the web server to access the previous directory
    .setPublicPath('/build')
    .cleanupOutputBeforeBuild()
    .addEntry("base/base", [
        "./assets/js/base.js",
        "./assets/scss/base/base.scss"
    ])
    .addEntry("base/create_edit_user", [
        "./assets/js/share/form.js",
    ])
    .addEntry("base/list_user", [
        "./assets/js/list_user.js",
    ])
    .addEntry("base/list_user_group", [
        "./assets/js/list_user_group.js",
    ])
    .createSharedEntry('vendor', [
        'jquery',
        'bootstrap',
        'bootstrap/scss/bootstrap.scss',
        'datatables.net-bs4/css/dataTables.bootstrap4.css',
        'datatables.net-buttons-bs4/css/buttons.bootstrap4.css',
        'datatables.net-select-bs4/css/select.bootstrap4.css'
    ])
    .enableSourceMaps(!Encore.isProduction())
    .enableSassLoader()
    .autoProvidejQuery()
    // uncomment to create hashed filenames (e.g. app.abc123.css)
    // .enableVersioning(Encore.isProduction())

    // uncomment to define the assets of the project
    // .addEntry('js/app', './assets/js/app.js')
    // .addStyleEntry('css/app', './assets/css/app.scss')
;

module.exports = Encore.getWebpackConfig();
