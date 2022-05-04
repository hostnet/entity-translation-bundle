<?php
/**
 * @copyright 2016-2018 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Bundle\EntityTranslationBundle\Functional\Fixtures\Enum;

final class PostStatus
{
    public const AWAITING_APPROVAL = 'awaiting approval';
    public const VISIBLE           = 'visible';
    public const DELETED           = 'deleted';
    public const HIDDEN            = 'hidden';

    /**
     * @codeCoverageIgnore private by design because this is an ENUM class
     */
    private function __construct()
    {
    }
}
