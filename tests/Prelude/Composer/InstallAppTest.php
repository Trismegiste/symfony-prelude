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
    protected $generated;

    protected function setUp()
    {
        $console = $this->getMockBuilder('Composer\IO\ConsoleIO')
                ->setMethods(['write', 'ask'])
                ->getMock();
        $console->expects($this->any())
                ->method('ask')
                ->will($this->returnValue('myValue'));

        $baseDir = sys_get_temp_dir();
        if (!file_exists($baseDir)) {
            mkdir($baseDir, 0777, true);
        }
        $defaultCfg['parameters'] = ['oneParam' => 'defaultValue'];
        $dest = $baseDir . 'default.yml';
        file_put_contents($dest, \Symfony\Component\Yaml\Yaml::dump($defaultCfg));
        $this->generated = $baseDir . 'dummy' . '.yml';
        // make sure old tests are deleted
        if (file_exists($this->generated)) {
            unlink($this->generated);
        }

        $this->sut = new InstallApp(sys_get_temp_dir(), $console, 'dummy');
    }

    public function testExecute()
    {
        $this->assertFileNotExists($this->generated);
        $this->sut->execute();
        $this->assertFileExists($this->generated);

        $customCfg = \Symfony\Component\Yaml\Yaml::parse(file_get_contents($this->generated));
        $this->assertEquals('myValue', $customCfg['parameters']['oneParam']);
    }

}