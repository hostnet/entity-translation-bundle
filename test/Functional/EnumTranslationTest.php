<?php
/**
 * @copyright 2016-2018 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Bundle\EntityTranslationBundle\Functional;

use Hostnet\Bundle\EntityTranslationBundle\Functional\Fixtures\Enum\PostStatus;
use Hostnet\Bundle\EntityTranslationBundle\Functional\Fixtures\Enum\ReplyStatus;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Tests if the FooBundle enum translations and app translations can be loaded.
 *
 * The Fixtures directory is considered the "app" directory as the kernel resides there.
 */
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
        /** @var TranslatorInterface $translator */
        $translator = static::$kernel->getContainer()->get('translator');

        self::assertSame($expected_translation, $translator->trans($key, [], $object_class, $locale));
    }

    public static function translationProvider()
    {
        return [
            [PostStatus::class, PostStatus::AWAITING_APPROVAL, 'I am awaiting approval', 'en'],
            [PostStatus::class, PostStatus::AWAITING_APPROVAL, 'Ik wacht op verwijdering', 'nl'],
            [PostStatus::class, PostStatus::DELETED, 'I am deleted', 'en'],
            [PostStatus::class, PostStatus::DELETED, 'Ik ben verwijderd', 'nl'],
            [PostStatus::class, PostStatus::VISIBLE, 'I am visible', 'en'],
            [PostStatus::class, PostStatus::VISIBLE, 'Ik ben zichtbaar', 'nl'],
            [PostStatus::class, PostStatus::HIDDEN, 'Hidden (vendor file)', 'en'],
            [PostStatus::class, 'henk', 'henk', 'en'],
            [null, 'henk', 'henk', 'nl'],
            [ReplyStatus::class, ReplyStatus::CLOSED, 'closed', 'en'],
            [ReplyStatus::class, ReplyStatus::OPEN, 'open', 'en'],
        ];
    }

    public function testverifyDefaultAppMessages()
    {
        /** @var TranslatorInterface $translator */
        $translator = static::$kernel->getContainer()->get('translator');

        self::assertSame('correct', $translator->trans('to_verify_the_translations_are_loaded'));
    }
}
