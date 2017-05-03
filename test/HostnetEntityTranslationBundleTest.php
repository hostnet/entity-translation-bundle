<?php
namespace Hostnet\Bundle\EntityTranslationBundle;

use Hostnet\Bundle\EntityTranslationBundle\DependencyInjection\EnumTranslationCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @covers \Hostnet\Bundle\EntityTranslationBundle\HostnetEntityTranslationBundle
 * @author Yannick de Lange <ydelange@hostnet.nl>
 */
class HostnetEntityTranslationBundleTest extends \PHPUnit_Framework_TestCase
{
    public function testBuild()
    {
        $container = new ContainerBuilder();

        $bundle = new HostnetEntityTranslationBundle();
        $bundle->build($container);

        $this->assertArrayHasKey('hostnet_entity_translation.enum.loader', $container->getDefinitions());

        $found = false;

        foreach ($container->getCompilerPassConfig()->getBeforeOptimizationPasses() as $pass) {
            if (!$pass instanceof EnumTranslationCompilerPass) {
                continue;
            }

            $found = true;
        }

        $this->assertTrue($found, 'Expected to find an optimization pass instance of the EnumTranslationCompilerPass');
    }
}
