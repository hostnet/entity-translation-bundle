<?php
namespace Hostnet\Bundle\EntityTranslationBundle;

use Hostnet\Bundle\EntityTranslationBundle\DependencyInjection\EnumTranslationCompilerPass;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @author Yannick de Lange <ydelange@hostnet.nl>
 */
class HostnetEntityTranslationBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/Resources/config'));
        $loader->load('services.yml');

        $container->addCompilerPass(new EnumTranslationCompilerPass());
    }
}
