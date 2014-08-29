<?php

/*
 * Prelude
 */

namespace Trismegiste\Prelude\Composer;

use Composer\Script\Event;

/**
 * Script is an auto-installer for platform specific parameters
 */
class Script
{

    /**
     * Install script called by Composer
     *
     * @param \Composer\Script\Event $event
     */
    static public function installPlatform(Event $event)
    {
        $cfg = $event->getComposer()->getPackage()->getExtra();
        $cli = new InstallApp($cfg['symfony-app-dir'] . static::getPlatformSubdir(), $event->getIO(), static::getPlatformName());

        $cli->execute();
    }

    static public function getPlatformSubdir()
    {
        return '/config/platform/';
    }

    /**
     * Gets the platform's name
     * 
     * @return string
     */
    static public function getPlatformName()
    {
        return php_uname('n');
    }

}