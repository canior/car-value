<?php

namespace App\Service;

interface ImportSoldCarServiceInterface
{
    public function import(array $record): bool;
}
