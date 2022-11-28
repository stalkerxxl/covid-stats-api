<?php

namespace App\Message;

final class UpdateStatsByCountry
{
     private string $slug;

     public function __construct(string $countrySlug)
     {
         $this->slug = $countrySlug;
     }

    public function getSlug(): string
    {
        return $this->slug;
    }
}
