const isProdMode = process.env.NODE_ENV === 'production';

module.exports = function (params) {
    const modules = `${params.basePath}/Resources/app/storefront/node_modules`;
    const vueAlias = isProdMode ?
        `${modules}/vue/dist/vue.esm-browser.prod.js`:
        `${modules}/vue/dist/vue.esm-browser.js`;
    return {
        resolve: {
            alias: {
                vue: vueAlias
            },
            modules: [
                modules
            ]
        },
        module: {
            rules: [
                {
                    test: /\.html$/i,
                    loader: 'html-loader',
                }
            ]
        },
    };
};
