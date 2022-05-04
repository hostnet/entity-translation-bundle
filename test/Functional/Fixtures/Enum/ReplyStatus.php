<?php
/**
 * @copyright 2016-2018 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Bundle\EntityTranslationBundle\Functional\Fixtures\Enum;

final class ReplyStatus
{
    public const CLOSED = 0;
    public const OPEN   = 1;

    /**
     * @codeCoverageIgnore private by design because this is an ENUM class
     */
    private function __construct()
    {
    }
}
