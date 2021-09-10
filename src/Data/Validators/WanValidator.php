<?php


namespace XbNz\AsusRouter\Data\Validators;


use Illuminate\Support\Collection;
use XbNz\AsusRouter\Data\DataObject;
use XbNz\AsusRouter\Exceptions\NoPublicIpDetectedException;

class WanValidator implements ValidatorInterface
{
    public function validate(string $terminalOutput): Collection|bool
    {
        $ipAddresses = collect(explode(PHP_EOL, $terminalOutput));
        $validIps = $ipAddresses->filter(function ($ip){
            return filter_var(
                $ip,
                FILTER_VALIDATE_IP,
                FILTER_FLAG_NO_PRIV_RANGE|
                FILTER_FLAG_NO_RES_RANGE
            );
        });

        if (! $validIps){
            throw new NoPublicIpDetectedException('Was not able to find a public IP address');
        }
        return $validIps;
    }

    public function supports()
    {
        return strtolower('wan');
    }
}