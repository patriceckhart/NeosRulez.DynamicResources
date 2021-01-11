# Dynamic resources for Neos CMS

A package for Neos CMS which render dynamic css and javascript resources.

## Installation

The NeosRulez.DynamicResources package is listed on packagist (https://packagist.org/packages/neosrulez/dynamicresources) - therefore you don't have to include the package in your "repositories" entry any more.

Just run:

```
composer require neosrulez/dynamicresources
```

## Usage

```
NeosRulez:
  DynamicResources:
    resources:
      head:
        scripts:
          testJs: 'resource://NeosRulez.DynamicResources/Private/JavaScript/test.js'
          testJs2: 'resource://NeosRulez.DynamicResources/Private/JavaScript/test2.js'
        styles:
          testCss: 'resource://NeosRulez.DynamicResources/Private/Styles/test.scss'
          testCss2: 'resource://NeosRulez.DynamicResources/Private/Styles/test2.scss'
      footer:
        scripts:
          testJs: 'resource://NeosRulez.DynamicResources/Private/JavaScript/test.js'
          testJs2: 'resource://NeosRulez.DynamicResources/Private/JavaScript/test2.js'
        styles:
          testJs: 'resource://NeosRulez.DynamicResources/Private/Styles/test.scss'
          testJs2: 'resource://NeosRulez.DynamicResources/Private/Styles/test2.scss'
}
```

## Author

* E-Mail: mail@patriceckhart.com
* URL: http://www.patriceckhart.com 