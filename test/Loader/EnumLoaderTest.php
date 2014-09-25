<?php
namespace Hostnet\Bundle\EntityTranslationBundle\Loader;

use Hostnet\Bundle\EntityTranslationBundle\Mock\MockEnum;
use Symfony\Component\Translation\Loader\YamlFileLoader;
use Symfony\Component\Translation\Translator;

/**
 * @covers Hostnet\Bundle\EntityTranslationBundle\Loader\EnumLoader
 */
class EnumLoaderTest extends \PHPUnit_Framework_TestCase
{
    private $translator;

    public function setUp()
    {
        $yml_loader = new YamlFileLoader();
        $loader     = new EnumLoader($yml_loader);

        $this->translator = new Translator("en");
        $this->translator->addLoader("enum", $loader);
        $this->translator->addResource(
            "enum",
            __DIR__ . "/../Mock/Resources/translations/enum.en.yml",
            "en",
            MockEnum::class
        );
    }

    public function testTranslation()
    {
        $this->assertEquals(
            "Foo",
            $this->translator->trans(MockEnum::FOO, [], MockEnum::class)
        );
        $this->assertEquals(
            "Not so Bar",
            $this->translator->trans(MockEnum::BAR, [], MockEnum::class)
        );
        $this->assertEquals(
            "foo_bar",
            $this->translator->trans(MockEnum::FOO_BAR, [], MockEnum::class)
        );
        $this->assertEquals(
            "1",
            $this->translator->trans(MockEnum::FOO)
        );
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testNonEnumLoad()
    {
        $yml_loader = new YamlFileLoader();
        $loader     = new EnumLoader($yml_loader);
        $translator = new Translator("en");

        $translator->addLoader("enum", $loader);
        $translator->addResource(
            "enum",
            __DIR__ . "/../Mock/Resources/translations/enum.en.yml",
            "en",
            "phpunit"
        );
        $translator->trans(0, [], "phpunit");
    }
}
