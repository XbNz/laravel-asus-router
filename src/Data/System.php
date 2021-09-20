<?php


namespace XbNz\AsusRouter\Data;


use Illuminate\Support\Collection;
use Spatie\Ssh\Ssh;

class System extends DataObject
{
    public function getRsaKeyList(): Collection
    {
        $rawOutput = app(Ssh::class)
            ->execute('nvram get sshd_authkeys')->getOutput();

        $validator = $this->giveValidatorFor('system-rsa-list');
        return $validator->validate($rawOutput)->flatten();
    }

    public function getRouterModel(): string | null
    {
        $rawOutput = app(Ssh::class)
            ->execute('nvram get model')->getOutput();

        return trim($rawOutput) ?? null;
    }
}