<?php

namespace XbNz\AsusRouter\Data\Validators;

use Illuminate\Support\Collection;

class SystemRsaListValidator implements ValidatorInterface
{
    public function validate(string $terminalOutput): Collection|bool
    {
        preg_match_all("/[0-9A-Za-z+\/]+[=]/", $terminalOutput, $matches);
        return collect($matches);
    }

    public function supports(): string
    {
        return 'system-rsa-list';
    }
}