.. index::
    single: Installation

Installation
============

The easiest way to install ``SonataFormBundle`` is to require it with Composer:

.. code-block:: bash

    $ composer require sonata-project/form-extensions

Alternatively, you could add a dependency into your ``composer.json`` file directly.

Now, enable the bundle in ``bundles.php`` file:

.. code-block:: php

    <?php

    // config/bundles.php

    return [
        //...
        Sonata\Form\SonataFormBundle::class => ['all' => true],
    ];

.. note::
    If you are not using Symfony Flex, you should enable bundles in your
    ``AppKernel.php``.

.. code-block:: php

    <?php
    // app/AppKernel.php

    public function registerBundles()
    {
        return array(
            // ...
            new Sonata\Form\SonataFormBundle(),
            // ...
        );
    }

Configuration
=============

.. configuration-block::

    .. code-block:: yaml

        # config/packages/sonata.yaml

        sonata_form:
            form:
                mapping:
                    enabled: false

.. note::
    If you are not using Symfony Flex, this configuration should be added
    to ``app/config/config.yml``.

When using bootstrap, some widgets need to be wrapped in a special ``div`` element
depending on whether you are using the standard style for your forms or the
horizontal style.

If you are using the horizontal style, you will need to configure the
corresponding configuration node accordingly:

.. configuration-block::

    .. code-block:: yaml

        # config/packages/sonata_form.yaml

        sonata_form:
            form_type: horizontal

.. note::
    If you are not using Symfony Flex, this configuration should be added
    to ``app/config/config.yml``.

Please note that if you are using the admin bundle, this is actually optional:
The core bundle extension will detect if the configuration node that deals with
the form style in the admin bundle is set and will configure the core bundle for you.
