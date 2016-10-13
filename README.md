entity-translation-bundle
=========================

This bundle allows the automation of translating an enum value to a human readable string, for example, displaying a status text instead of the enum-value of a status code.

In it's essence, it allows to map a value to a translation within the domain that is the class name. What this means in practice is that you can use it as follows:
```php
$status_text = $translator->trans(SetupStatus::DONE, [], SetupStatus::class);
```
Or in a twig template:
```twig
{{ constant('Amce\\ExampleBundle\\Entity\\SetupStatus::DONE') | trans([], 'Amce\\ExampleBundle\\Entity\\SetupStatus') }}
```

Requirements
------------
The entity translation bundle requires at least php 5.4 and the symfony translation component. For specific requirements, please check [composer.json](../master/composer.json)

Installation
------------

Installing is pretty straightforward, this package is available on [packagist](https://packagist.org/packages/hostnet/entity-translation-bundle).

#### Example

```javascript
    "require" : {
        "hostnet/entity-translation-bundle" : "dev-master"
    }
```

#### Register The Bundle in your AppKernel
This bundle makes use of the translator which is registered by the framework bundle. So make sure you register this bundle after the `FrameworkBundle`.

```php
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = [
            // ...
            new Hostnet\Bundle\EntityTranslationBundle\HostnetEntityTranslationBundle()
            // ...
        ];

        return $bundles;
    }
}
```

Usage
------------
Simply add an `enum.en.yml` to the translations folder in the Resources folder of one of your bundles. This will contain the translations for a given enum. The translation keys are the fully qualified namespaces in lowercase and an `_` between CamelCase words. So for instance the enum `Amce\ExampleBundle\Entity\SetupStatus` would become `amce.example_bundle.entity.setup_status`.

Consider the following class:
```php
<?php
namespace Amce\ExampleBundle\Entity;

final class SetupStatus
{
    const PENDING           = 1;
    const DONE              = 2;
    const ERROR             = 3;
    const REVERTING_CHANGES = 4;
}
```
Your `ExampleBundle/Resources/translations/enum.en.yml` could look as followed:
```yaml
amce:
    example_bundle:
        entity:
            setup_status:
                pending           : Installation Pending.
                done              : Installation Complete.
                error             : An error occured.
                reverting_changes : Reverting changes.
```

The translator will then pick up all enum classes defined in your translation file.
