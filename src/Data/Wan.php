<?php


namespace XbNz\AsusRouter\Data;


use Spatie\Ssh\Ssh;
use XbNz\AsusRouter\Data\Validators\ValidatorInterface;

class Wan extends DataObject
{
    public function getIpList(): \Illuminate\Support\Collection
    {
        $rawOutput = app(Ssh::class)
            ->execute([
                'nvram get wan_ipaddr',
                'nvram get wan0_ipaddr',
                'nvram get wan1_ipaddr',
            ])->getOutput();

        $validator = $this->giveValidatorFor('wan-ip-list');
        return $validator->validate($rawOutput);
    }
}