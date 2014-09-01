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

    protected $baseDir;
    protected $console;

    protected function setUp()
    {
        $this->console = $this->getMockBuilder('Composer\IO\ConsoleIO')
                ->setMethods(['write', 'ask'])
                ->getMock();
        $this->console->expects($this->any())
                ->method('ask')
                ->will($this->returnValue('myValue'));

        $this->baseDir = sys_get_temp_dir() . '/';
    }

    public function testMissingDirectory()
    {
        $sut = new InstallApp(rand(), $this->console, 'dummy');
        $this->console->expects($this->once())
                ->method('write')
                ->with($this->stringContains('missing'));

        $sut->execute();
    }

    public function testMissingConfig()
    {
        $sut = new InstallApp($this->baseDir, $this->console, 'dummy');
        $this->console->expects($this->once())
                ->method('write')
                ->with($this->stringContains('missing'));

        $sut->execute();
    }

    public function testExecute()
    {
        $sut = new InstallApp($this->baseDir, $this->console, 'dummy');

        $defaultCfg['parameters'] = ['oneParam' => 'defaultValue'];
        $dest = $this->baseDir . 'default.yml';
        file_put_contents($dest, \Symfony\Component\Yaml\Yaml::dump($defaultCfg));
        $generated = $this->baseDir . 'dummy' . '.yml';
        // make sure old tests are deleted
        if (file_exists($generated)) {
            unlink($generated);
        }

        $this->assertFileNotExists($generated);
        $sut->execute();
        $this->assertFileExists($generated);

        $customCfg = \Symfony\Component\Yaml\Yaml::parse(file_get_contents($generated));
        $this->assertEquals('myValue', $customCfg['parameters']['oneParam']);
    }

}