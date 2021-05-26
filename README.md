# Dynamic resources for Neos CMS

A package for Neos CMS which render and cache dynamic CSS (SASS) and Javascript resources.

## Installation

The NeosRulez.DynamicResources package is listed on packagist (https://packagist.org/packages/neosrulez/dynamicresources) - therefore you don't have to include the package in your "repositories" entry any more.

Just run:

```
composer require neosrulez/dynamicresources
```

## Usage

```yaml
NeosRulez:
  DynamicResources:
    site:
      resources:
        head:
          scripts:
            acmeHeaderJs: 'resource://Acme.Package/Private/JavaScript/script.js'
          styles:
            acmeHeaderCss: 'resource://Acme.Package/Private/Styles/styles.scss'
        footer:
          scripts:
            acmeFooterJs: 'resource://Acme.Package/Private/JavaScript/footerscript.js'
          styles:
            acmeFooterCss: 'resource://Acme.Package/Private/Styles/footerstyles.scss'
```

## Author

* E-Mail: mail@patriceckhart.com
* URL: http://www.patriceckhart.com 
