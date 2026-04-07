# Webwirkung Vibraplast Theme for Shopware 6
This is the Vibraplast theme based on the webwirkung starter theme for Shopware 6. 

## How to use this theme
1. Clone it from github
2. Change the composer.json file and add your theme name and also adjust namespaces
3. Change the name of WebwirkungVibraplastTheme.php file to your theme name
4. Enter the file and change the namespace according to your theme name
5. Go to `views/storefront/layout/meta.html.twig` and replace the theme name for the GTM config with your new theme name
6. Go to `views/storefront/base.html.twig` and replace the theme name for the GTM config with your new theme name 
7. Replace the preview img of the theme `app/storefront/src/assets/preview.png`
8. Install the theme via composer by
```shell
 composer require webwirkung/your-theme-name
```

## What this theme brings within
It comes with the following functionality:

### Webpack
This theme comes with webpack 5.15.0. Find the webpack configuration here: 
```shell
src/Resources/app/storefront/build/webpack.config.js
```

### Theme config

#### Theme base
The theme has Bootstrap as its base. Find the configuration in the theme.json file: 
- "@StorefrontBootstrap"

#### Customizable vars
In the backend you can configure the following variables find the scss variable in the brackets for each variable. Of course change it in the theme.json file for your project first.
- Primary color ($sw-color-brand-primary)
- Secondary color ($sw-color-brand-secondary)
- Secondary color ($sw-color-brand-secondary)
- Border color ($sw-border-color)
- Font Family base ($sw-font-family-base)
- Font Family headline ($sw-font-family-headline)
- Text color base ($sw-text-color)
- Text color headline ($sw-headline-color)
- Buy button color ($sw-color-buy-button)

System Colors
- Danger color ($sw-color-danger)
- Success color ($sw-color-success)
- Info color ($sw-color-info)
- Price color ($sw-color-price)


### New cms blocks category *Webwirkung Blocks*
This theme also comes with the **Webwirkung Blocks** (webwirkung-blocks) category for CMS Blocks in the Shopping experiences.  

#### How to use it
If you are creating a new cms block just add  
`webwirkung-blocks` as the category when registering the block.
With this the CMS Block will be placed in the **Webwirkung Blocks** Category in the backend of Shopping Experiences.

The whole thing is handled in this module:  
`app/administration/src/module/webwirkung-cms-blocks`

## Special Functions

### Products on request
The request form is published inside a shopping experience. For the modal link for the request form on the product detail page you need to select the shopping experience in the theme config.
