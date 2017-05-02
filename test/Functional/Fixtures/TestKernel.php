<?php
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;

class TestKernel extends Kernel
{
    /**
     * {@inheritdoc}
     */
    public function registerBundles()
    {
        return array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Hostnet\Bundle\EntityTranslationBundle\HostnetEntityTranslationBundle(),
            new Hostnet\Bundle\EntityTranslationBundle\Functional\Fixtures\FooBundle\FooBundle(),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config.yml');
    }

    public function getCacheDir()
    {
        return dirname(__DIR__).'/../../var/cache';
    }

    public function getLogDir()
    {
        return dirname(__DIR__).'/../../var/logs';
    }
}
