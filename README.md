# Extended Framework Bundle

This bundle extends the Symfony framework by providing features *genericizing* *homogeneously* common development processes in order to build faster *solid*ly architectured Symfony applications.

This bundle is still in ALPHA.

Contributions are welcome.

Refer to the [demo application](https://github.com/opportus/demo-rest-api) for concrete usage examples.

## To Do

- Improve the documentation
- Implement tests

## Index

- [Installation](#installation)

## Installation

### Applications that use Symfony Flex

Open a command console, enter your project directory and execute:

```console
$ composer require opportus/extended-framework-bundle
```

### Applications that do not use Symfony Flex

#### Step 1 - Download the Bundle

Open a command console, enter your project directory and execute the following command to download the latest stable version of this bundle:

```console
$ composer require opportus/extended-framework-bundle
```

#### Step 2 - Enable the Bundle

Then, enable the bundle by adding it to the list of registered bundles in the `app/AppKernel.php` file of your project:

```php
<?php
// app/AppKernel.php

// ...
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // ...
            new Opportus\ExtendedFrameworkBundle\OpportusExtendedFrameworkBundle(),
        );

        // ...
    }

    // ...
}
```