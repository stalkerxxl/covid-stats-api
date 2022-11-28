<?php

namespace App\Message;

final class UpdateCountry
{
    private ?array $offlineData;

    public function __construct(array $offlineData = null)
    {
        $this->offlineData = $offlineData;
    }

    public function getOfflineData(): array
    {
        return $this->offlineData;
    }
}
