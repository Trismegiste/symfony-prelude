<?php

/*
 * Prelude
 */

namespace tests\Prelude;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use tests\example\AppKernel;

/**
 * KernelTest tests the Kernel
 */
class KernelTest extends WebTestCase
{

    static protected $tmpFile;

    static public function setupBeforeClass()
    {
        static::$class = 'tests\example\AppKernel';

        $refl = new \ReflectionClass(static::$class);
        $kernelDir = dirname($refl->getFileName());
        static::$tmpFile = $kernelDir . '/config/platform/' . php_uname('n') . '.yml';
        copy($kernelDir . '/config/platform/default.yml', static::$tmpFile);
    }

    static public function tearDownAfterClass()
    {
        unlink(static::$tmpFile);
    }

    public function getDifferentConfig()
    {
        return [
            ['test', 7],
            ['prod', 5],
            ['dev', 7]
        ];
    }

    /**
     * @dataProvider getDifferentConfig
     */
    public function testKernel($name, $cardinal)
    {
        $client = self::createClient(['environment' => $name, 'debug' => true]);
        $this->assertCount($cardinal, $client->getKernel()->getBundles());
    }

}