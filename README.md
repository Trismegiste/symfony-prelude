# Prelude to Symfony [![Build Status](https://travis-ci.org/Trismegiste/symfony-prelude.svg?branch=master)](https://travis-ci.org/Trismegiste/symfony-prelude)
An easy way to start a Symfony app

## What
Ths small lib does 3 things :

* Provides a fat-free kernel for Symfony 2 with only essential bundles
* Provides an auto-installer script for platform-specific parameters
* Inject a default parameter "developer.name" to customize your configuration when many developers are involved

## Why
Reduce the rate of "But it works on my computer (or my VM) !"

It starts for 3 reasons :

* the lost so many times of the infamous parameters.yml
* the AppKernel class is not [OCP][1]
* customizing parameters automatically between a team

## When
Before you start to mess your AppKernel and parameters.yml. When you don't need
fatty fat bundles like Doctrine or Swiftmailer or ide-validation-less annotations.

## Where
Add this lib to the composer.json of your Symfony project. Add the install script for 
Composer. Rewrite the AppKernel like you can see in [iinano][2]. Remove parameters.yml
(and its reference). Remove Incenteev reference in composer.json.

## Y U NO use Incenteev ?
This lib does not provide a way to prepare a parameters.yml for the preprod server,
for example, so your sysadmin does not get mad when he's installing your app at 1:00 AM.
Only the file for the production server needs to be in gitignore. 

Nonetheless, you can use both in the same project.

[1]: http://en.wikipedia.org/wiki/Open/closed_principle
[2]: https://github.com/Trismegiste/iinano/tree/master/app/AppKernel.php
