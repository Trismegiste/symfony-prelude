<?php

/*
 * Prelude
 */

namespace tests\Prelude\Composer;

use Trismegiste\Prelude\Composer\InstallApp;

/**
 * InstallAppTest tests InstallApp app
 */
class InstallAppTest extends \PHPUnit_Framework_TestCase
{

    protected $sut;
    protected $baseDir;

    protected function setUp()
    {
        $console = $this->getMockBuilder('Composer\IO\ConsoleIO')
                ->setMethods(['write', 'ask'])
                ->getMock();
        $console->expects($this->any())
                ->method('ask')
                ->will($this->returnValue('myValue'));

        $this->baseDir = sys_get_temp_dir() . '/';
        $this->sut = new InstallApp($this->baseDir, $console, 'dummy');
    }

    public function testExecute()
    {
        $defaultCfg['parameters'] = ['oneParam' => 'defaultValue'];
        $dest = $this->baseDir . 'default.yml';
        file_put_contents($dest, \Symfony\Component\Yaml\Yaml::dump($defaultCfg));
        $generated = $this->baseDir . 'dummy' . '.yml';
        // make sure old tests are deleted
        if (file_exists($generated)) {
            unlink($generated);
        }

        $this->assertFileNotExists($generated);
        $this->sut->execute();
        $this->assertFileExists($generated);

        $customCfg = \Symfony\Component\Yaml\Yaml::parse(file_get_contents($generated));
        $this->assertEquals('myValue', $customCfg['parameters']['oneParam']);
    }

    public function testMissingConfig()
    {
        unlink($this->baseDir . 'default.yml');
        $this->sut->execute();
    }

}