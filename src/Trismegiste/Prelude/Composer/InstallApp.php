<?php

/*
 * Prelude
 */

namespace Trismegiste\Prelude\Composer;

use Composer\IO\ConsoleIO;
use Symfony\Component\Yaml\Yaml;

/**
 * InstallApp is a CLI app for installing a Symfony app
 * (called by Composer script)
 * 
 * Main purpose: It decouples the install script from Composer
 * for easy testing/mockuping and does not violate Demeter's Law
 */
class InstallApp
{

    protected $symfonyCfgDir;
    protected $composerIO;
    protected $platformName;

    /**
     * Ctor
     * 
     * @param string $directory the symfony subdirectory for platform config files
     * @param ConsoleIO $io the composer IO console
     */
    public function __construct($directory, ConsoleIO $io, $platformName)
    {
        $this->symfonyCfgDir = $directory;
        $this->composerIO = $io;
        $this->platformName = $platformName;
    }

    /**
     * Runs the app
     */
    public function execute()
    {
        $plateformDir = $this->symfonyCfgDir;
        $template = $plateformDir . 'default.yml';
        if (!file_exists($template)) {
            $this->composerIO->write("<error>default configuration is missing in {$this->symfonyCfgDir}</error>");
        }

        $dest = $plateformDir . $this->platformName . '.yml';
        if (!file_exists($dest)) {
            $this->composerIO->write("<info>Configuring parameters for {$this->platformName} :</info>");

            $defaultParam = Yaml::parse($template);
            foreach ($defaultParam['parameters'] as $key => $val) {
                $override = $this->composerIO->ask("<question>$key</question> [$val] = ", $val);
                $newValues[$key] = $override;
            }
            $newConfig['parameters'] = $newValues;
            file_put_contents($dest, Yaml::dump($newConfig));

            $this->composerIO->write("Writing parameters to <comment>$dest</comment>");
        }
    }

}