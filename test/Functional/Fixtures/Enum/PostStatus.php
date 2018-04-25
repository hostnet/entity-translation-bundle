<?php
/**
 * @copyright 2016-2018 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Bundle\EntityTranslationBundle\Functional\Fixtures\Enum;

final class PostStatus
{
    const AWAITING_APPROVAL = 'awaiting approval';
    const VISIBLE           = 'visible';
    const DELETED           = 'deleted';
    const HIDDEN            = 'hidden';

    /**
     * @codeCoverageIgnore private by design because this is an ENUM class
     */
    private function __construct()
    {
    }
}
