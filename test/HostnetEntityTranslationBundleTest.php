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
    private $expected_services = [
        'hostnet_entity_translation.enum.loader'
    ];

    public function testBuild()
    {
        $container = new ContainerBuilder();

        $bundle = new HostnetEntityTranslationBundle();
        $bundle->build($container);

        $definitions = array_keys($container->getDefinitions());
        $this->assertEquals(count($this->expected_services), count($definitions));

        foreach ($this->expected_services as $service) {
            $this->assertContains($service, $definitions);
        }

        $passes = $container->getCompilerPassConfig()->getBeforeOptimizationPasses();
        $this->assertEquals(1, count($passes));
        $this->assertInstanceOf(EnumTranslationCompilerPass::class, $passes[0]);
    }
}
