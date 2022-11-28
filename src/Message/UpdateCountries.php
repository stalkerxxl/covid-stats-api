<?php

namespace App\Message;

final class UpdateCountries
{
    private ?array $offlineData;

    public function __construct(array $offlineData = null)
    {
        $this->offlineData = $offlineData;
    }

    public function getOfflineData(): ?array
    {
        return $this->offlineData;
    }
}
