<?php
declare(strict_types=1);

namespace LunaPress\Wp\I18n\Function\GetUserLocale;

use LunaPress\Wp\I18nContracts\Function\GetUserLocale\IGetUserLocaleFunction;

defined('ABSPATH') || exit;

final class GetUserLocale implements IGetUserLocaleFunction
{
    private int $userId = 0;

    public function userId(int $userId): static
    {
        $this->userId = $userId;
        return $this;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function rawArgs(): array
    {
        return [
            $this->getUserId()
        ];
    }

    public function executeWithArgs(array $args): string
    {
        return get_user_locale(...$args);
    }
}
