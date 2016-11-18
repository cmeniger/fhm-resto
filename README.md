FHM-TOOLS-V2
------------

Clone project
-------------

From docker terminal :

    cd /var/www
    git clone http://gitlab.fhmsolutions.com/fhmsolutions/fhm-tools-v2.git
    cd fhm-tools-v2/

HOST config
-----------

Linux
-----
Open a new terminal and edit :

    sudo nano /etc/hosts

And add the following line :

    127.0.0.1 fhm-tools-v2.local

Symfony Standard Edition
========================

Welcome to the Symfony Standard Edition - a fully-functional Symfony2
application that you can use as the skeleton for your new applications.

This document contains information on how to download, install, and start
using Symfony. For a more detailed explanation, see the [Installation][1]
chapter of the Symfony Documentation.

1) Installing the vendor for the project
----------------------------------------

When it comes to installing the Symfony Standard Edition, you have the
following options.

### Use Composer (*recommended*)

As Symfony uses [Composer][2] to manage its dependencies, the recommended way
to create a new project is to use it.

If you don't have Composer yet, download it following the instructions on
http://getcomposer.org/ or just run the following command:

In docker :

    cd /var/www/fhm-tools-v2
    composer selfupdate
    composer install
    chmod -R 777 var/cache var/logs

2) Checking your System Configuration
-------------------------------------

Before starting coding, make sure that your local system is properly
configured for Symfony.

Execute the check script from the command line:

    php bin/symfony_requirements

Or access the `config.php` script from a browser:

    http://fhm-tools-v2.local/config.php

If you get any warnings or recommendations, fix them before moving on.

3) Browsing the Demo Application
--------------------------------

Congratulations! You're now ready to use Symfony.

From the `config.php` page, click the "Bypass configuration and go to the
Welcome page" link to load up your first Symfony page.

You can also use a web-based configurator by clicking on the "Configure your
Symfony Application online" link of the `config.php` page.

To see a real-live Symfony page in action, access the following page:

    http://fhm-tools-v2.local/app_dev.php

4) Add FOSUser users for sending email
--------------------------------------

To send email, you must create these users and give admin rights by running the following command :

    php app/console fos:user:create fhm support@fhmsolutions.com fhm
    php app/console fos:user:create admin admin@fhm-tools-v2.com admin
    php app/console fos:user:create noreply noreply@fhm-tools-v2.com noreply
    php app/console fos:user:promote fhm ROLE_SUPER_ADMIN
    php app/console fos:user:promote admin ROLE_ADMIN

You can connect to the application with these credentials :

    http://fhm-tools-v2.local/app_dev.php/login

5) Useful command line
----------------------

Generate static files for integration :

    npm install
    bower install
    gulp