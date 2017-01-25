<?php
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
