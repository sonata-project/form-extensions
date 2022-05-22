.. index::
    single: Installation

Installation
============

The easiest way to install ``form-extensions`` is to require it with Composer:

.. code-block:: bash

    composer require sonata-project/form-extensions

Alternatively, you could add a dependency into your ``composer.json`` file directly.

Now, enable the bundle in ``bundles.php`` file::

    // config/bundles.php

    return [
        // ...
        Sonata\Form\Bridge\Symfony\Bundle\SonataFormBundle::class => ['all' => true],
    ];

Configuration
=============

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

Please note that if you are using the admin bundle, this is actually optional:
The core bundle extension will detect if the configuration node that deals with
the form style in the admin bundle is set and will configure the core bundle for you.
