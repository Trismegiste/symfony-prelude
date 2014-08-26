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

    public function testKernelTest()
    {
        $client = self::createClient(['environment' => 'test', 'debug' => true]);
        $this->assertCount(7, $client->getKernel()->getBundles());
    }

    public function testKernelProd()
    {
        $client = self::createClient(['environment' => 'prod', 'debug' => true]);
        $this->assertCount(5, $client->getKernel()->getBundles());
    }

}