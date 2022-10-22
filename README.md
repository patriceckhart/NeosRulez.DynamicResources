# Dynamic resources for Neos CMS

A package for Neos CMS which render and cache dynamic CSS (scss) and Javascript resources.

## Installation

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
            acmeHeaderJs: 'resource://Acme.Package/Private/JavaScript/Scripts.js'
          styles:
            acmeHeaderCss: 'resource://Acme.Package/Private/Styles/Styles.scss'
        footer:
          scripts:
            acmeFooterJs: 'resource://Acme.Package/Private/JavaScript/Footer/Scripts.js'
          styles:
            acmeFooterCss: 'resource://Acme.Package/Private/Styles/Footer/Styles.scss'
```

## Author

* E-Mail: mail@patriceckhart.com
* URL: http://www.patriceckhart.com 
