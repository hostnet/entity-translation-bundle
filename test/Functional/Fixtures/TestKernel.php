<?php
/**
 * @copyright 2016-present Hostnet B.V.
 */
declare(strict_types=1);

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;

class TestKernel extends Kernel
{
    /**
     * {@inheritdoc}
     */
    public function registerBundles()
    {
        return [
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Hostnet\Bundle\EntityTranslationBundle\Functional\Fixtures\FooBundle\FooBundle(),
            new Hostnet\Bundle\EntityTranslationBundle\HostnetEntityTranslationBundle(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__ . '/config/config.yml');
    }

    public function getCacheDir()
    {
        return dirname(__DIR__) . '/../../var/cache';
    }

    public function getLogDir()
    {
        return dirname(__DIR__) . '/../../var/logs';
    }
}
