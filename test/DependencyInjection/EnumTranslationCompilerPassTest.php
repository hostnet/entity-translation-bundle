<?php
/**
 * @copyright 2014-2018 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Bundle\EntityTranslationBundle\DependencyInjection;

use Hostnet\Bundle\EntityTranslationBundle\Mock\MockEnum;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

/**
 * @covers \Hostnet\Bundle\EntityTranslationBundle\DependencyInjection\EnumTranslationCompilerPass
 */
class EnumTranslationCompilerPassTest extends TestCase
{
    /**
     * @dataProvider processLoadYamlFilesProvider
     */
    public function testProcessLoadYamlFiles($resource_path)
    {
        $resources  = realpath($resource_path);
        $container  = new ContainerBuilder();
        $translator = new Definition();
        $translator->setArguments([[], [], [], [
            'resource_files' => ['en' => [$resources]],
        ]]);
        $container->setDefinition('translator.default', $translator);

        $pass = new EnumTranslationCompilerPass();
        $pass->process($container);

        $calls = $translator->getMethodCalls();

        $this->assertEquals(1, count($calls));
        $this->assertEquals(['addResource', ['enum', $resources, 'en', MockEnum::class]], $calls[0]);
    }

    public function processLoadYamlFilesProvider()
    {
        yield [__DIR__ . '/../Mock/Resources/translations/enum.en.yml'];
        yield [__DIR__ . '/../Mock/Resources/translations/enum.en.yaml'];
    }

    public function testProcess()
    {
        $resources  = realpath(__DIR__ . '/../Mock/Resources/translations/enum.en.yml');
        $container  = new ContainerBuilder();
        $translator = new Definition();
        $translator->setArguments([[], [], [], [
            'resource_files' => ['en' => [$resources]],
        ]]);
        $container->setDefinition('translator.default', $translator);

        $pass = new EnumTranslationCompilerPass();
        $pass->process($container);

        $calls = $translator->getMethodCalls();

        $this->assertEquals(1, count($calls));
        $this->assertEquals(['addResource', ['enum', $resources, 'en', MockEnum::class]], $calls[0]);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testProcessNotYml()
    {
        $resources  = realpath(__DIR__ . '/../MockXml/Resources/translations/enum.en.xml');
        $container  = new ContainerBuilder();
        $translator = new Definition();
        $translator->setArguments([[], [], [], [
            'resource_files' => ['en' => [$resources]],
        ]]);

        $container->setDefinition('translator.default', $translator);

        $pass = new EnumTranslationCompilerPass();
        $pass->process($container);
    }

    public function testProcessNoTrans()
    {
        $container  = new ContainerBuilder();
        $translator = new Definition();
        $translator->setArguments([[], [], [], [
            'resource_files' => ['en' => []],
        ]]);

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
        $resources  = realpath(__DIR__ . '/../MockNoArray/Resources/translations/enum.en.yml');
        $container  = new ContainerBuilder();
        $translator = new Definition();
        $translator->setArguments([[], [], [], [
            'resource_files' => ['en' => [$resources]],
        ]]);

        $container->setDefinition('translator.default', $translator);

        $pass = new EnumTranslationCompilerPass();
        $pass->process($container);
    }
}
