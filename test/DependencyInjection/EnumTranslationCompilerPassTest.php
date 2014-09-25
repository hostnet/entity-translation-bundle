<?php
namespace Hostnet\Bundle\EntityTranslationBundle\DependencyInjection;

use Hostnet\Bundle\EntityTranslationBundle\Mock\MockEnum;
use Hostnet\Bundle\EntityTranslationBundle\MockNoTrans\MockNoTransEnum;
use Hostnet\Bundle\EntityTranslationBundle\MockXml\MockXmlEnum;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Hostnet\Bundle\EntityTranslationBundle\MockNoArray\MockNoArray;

/**
 * @covers Hostnet\Bundle\EntityTranslationBundle\DependencyInjection\EnumTranslationCompilerPass
 */
class EnumTranslationCompilerPassTest extends \PHPUnit_Framework_TestCase
{
    public function testProcess()
    {
        $container = new ContainerBuilder();
        $container->setParameter('kernel.bundles', ["Mock" => MockEnum::class]);

        $translator = new Definition();
        $container->setDefinition('translator.default', $translator);

        $pass = new EnumTranslationCompilerPass();
        $pass->process($container);

        $calls = $translator->getMethodCalls();

        $this->assertEquals(1, count($calls));
        $this->assertEquals(
            [
                "addResource",
                [
                    "enum",
                    realpath(__DIR__ . "/../Mock/Resources/translations/enum.en.yml"),
                    "en",
                    MockEnum::class
                ]
            ],
            $calls[0]
        );
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testProcessNotYml()
    {
        $container = new ContainerBuilder();
        $container->setParameter('kernel.bundles', ["MockXml" => MockXmlEnum::class]);

        $translator = new Definition();
        $container->setDefinition('translator.default', $translator);

        $pass = new EnumTranslationCompilerPass();
        $pass->process($container);
    }

    public function testProcessNoTrans()
    {
        $container = new ContainerBuilder();
        $container->setParameter('kernel.bundles', ["Mock" => MockNoTransEnum::class]);

        $translator = new Definition();
        $container->setDefinition('translator.default', $translator);

        $pass = new EnumTranslationCompilerPass();
        $pass->process($container);

        $calls = $translator->getMethodCalls();

        $this->assertEquals(0, count($calls));
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testProcessNoArray()
    {
        $container = new ContainerBuilder();
        $container->setParameter('kernel.bundles', ["MockNoArray" => MockNoArray::class]);

        $translator = new Definition();
        $container->setDefinition('translator.default', $translator);

        $pass = new EnumTranslationCompilerPass();
        $pass->process($container);
    }
}
