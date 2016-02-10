<?php
namespace Hostnet\Bundle\EntityTranslationBundle\Functional;

use Hostnet\Bundle\EntityTranslationBundle\Functional\Fixtures\Enum\PostStatus;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Translation\TranslatorInterface;

class EnumTranslationTest extends KernelTestCase
{
    protected function setUp()
    {
        static::bootKernel();
    }

    /**
     * @dataProvider translationProvider
     */
    public function testTranslations($object_class, $key, $expected_translation, $locale)
    {
        /* @var $translator TranslatorInterface */
        $translator = static::$kernel->getContainer()->get('translator');

        self::assertSame($expected_translation, $translator->trans($key, [], $object_class, $locale));
    }

    public function translationProvider()
    {
        return [
            [PostStatus::class, PostStatus::AWAITING_APPROVAL, 'I am awaiting approval', 'en'],
            [PostStatus::class, PostStatus::AWAITING_APPROVAL, 'Ik wacht op verwijdering', 'nl'],
            [PostStatus::class, PostStatus::DELETED, 'I am deleted', 'en'],
            [PostStatus::class, PostStatus::DELETED, 'Ik ben verwijderd', 'nl'],
            [PostStatus::class, PostStatus::VISIBLE, 'I am visible', 'en'],
            [PostStatus::class, PostStatus::VISIBLE, 'Ik ben zichtbaar', 'nl'],
            [PostStatus::class, 'henk', 'henk', 'en'],
            [null, 'henk', 'henk', 'nl'],
        ];
    }
}
