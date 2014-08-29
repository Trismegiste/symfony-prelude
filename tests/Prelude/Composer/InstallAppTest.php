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

    protected function setUp()
    {
        $console = $this->getMockBuilder('Composer\IO\ConsoleIO')
                ->setMethods(['write', 'ask'])
                ->getMock();
        $console->expects($this->any())
                ->method('ask')
                ->will($this->returnValue('myValue'));

        $this->sut = new InstallApp(sys_get_temp_dir(), $console, 'dummy');
    }

    public function testExecute()
    {
        $baseDir = sys_get_temp_dir();

        $defaultCfg['parameters'] = ['oneParam' => 'defaultValue'];
        $dest = $baseDir . 'default.yml';
        file_put_contents($dest, \Symfony\Component\Yaml\Yaml::dump($defaultCfg));
        $generated = $baseDir . 'dummy' . '.yml';
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
        unlink(sys_get_temp_dir() . 'default.yml');
        $this->sut->execute();
    }

}