FHM-TOOLS-V2
------------

1) HOST CONFIG
--------------

Open a new terminal and edit :

    sudo nano /etc/hosts

And add the following line :

    127.0.0.1 fhm-tools-v2.local

2) DOCKER - Clone project
-------------------------

In docker :

    cd /var/www
    git clone http://gitlab.fhmsolutions.com/fhmsolutions/fhm-tools-v2.git
    cd fhm-tools-v2/

Symfony Standard Edition
========================

Welcome to the Symfony Standard Edition - a fully-functional Symfony2
application that you can use as the skeleton for your new applications.

This document contains information on how to download, install, and start
using Symfony. For a more detailed explanation, see the [Installation][1]
chapter of the Symfony Documentation.

3) Installing the vendor for the project
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

4) Checking your System Configuration
-------------------------------------

Before starting coding, make sure that your local system is properly
configured for Symfony.

Execute the check script from the command line:

    php bin/symfony_requirements

Or access the `config.php` script from a browser:

    http://fhm-tools-v2.local/config.php

If you get any warnings or recommendations, fix them before moving on.

5) Browsing the Demo Application
--------------------------------

Congratulations! You're now ready to use Symfony.

From the `config.php` page, click the "Bypass configuration and go to the
Welcome page" link to load up your first Symfony page.

You can also use a web-based configurator by clicking on the "Configure your
Symfony Application online" link of the `config.php` page.

To see a real-live Symfony page in action, access the following page:

    http://fhm-tools-v2.local/app_dev.php

6) Add FOSUser users for sending email
--------------------------------------

To send email, you must create these users and give admin rights by running the following command :

    php app/console fos:user:create fhm support@fhmsolutions.com fhm
    php app/console fos:user:create admin admin@fhm-tools-v2.com admin
    php app/console fos:user:create noreply noreply@fhm-tools-v2.com noreply
    php app/console fos:user:promote fhm ROLE_SUPER_ADMIN
    php app/console fos:user:promote admin ROLE_ADMIN

You can connect to the application with these credentials :

    http://fhm-tools-v2.local/app_dev.php/login

7) Useful command line
----------------------

Generate static files for integration, in docker :

    cd /var/www/fhm-tools-v2
    npm install
    bower install --allow-root
    gulp
    
8) CHMOD
--------

When you install or update composer, npm or bower from your docker container, it will generate folder and files with the root user. So from you host machine, you will get a padlock on your generated files / folders and can’t update them. To fix it, from you host machine, open a terminal and set your user for these files / folders. 

    sudo chown -R [user]:[group] [path]
    sudo chown -R fhm-devops:fhm-devops ~/vagrant/www/fhm-tools-v2

