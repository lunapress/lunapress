<?php
declare(strict_types=1);

namespace LunaPress\Cli\Test\Fixture\I18n\Pot\Scanner\PhpStanScanner\Case01_Default\src;

use LunaPress\Foundation\Subscriber\AbstractFilterSubscriber;

final readonly class Subscriber extends AbstractFilterSubscriber
{
    public function callback(): callable
    {
        return fn() => null;
    }
}
