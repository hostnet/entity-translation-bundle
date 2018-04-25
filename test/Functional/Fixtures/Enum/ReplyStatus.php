<?php
/**
 * @copyright 2016-2018 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Bundle\EntityTranslationBundle\Functional\Fixtures\Enum;

final class ReplyStatus
{
    const CLOSED = 0;
    const OPEN   = 1;

    /**
     * @codeCoverageIgnore private by design because this is an ENUM class
     */
    private function __construct()
    {
    }
}
