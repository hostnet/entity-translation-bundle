<?php
/**
 * @copyright 2014-2018 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Bundle\EntityTranslationBundle;

use Hostnet\Bundle\EntityTranslationBundle\DependencyInjection\EnumTranslationCompilerPass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @covers \Hostnet\Bundle\EntityTranslationBundle\HostnetEntityTranslationBundle
 */
class HostnetEntityTranslationBundleTest extends TestCase
{
    public function testBuild(): void
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
