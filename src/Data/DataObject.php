<?php


namespace XbNz\AsusRouter\Data;

use Illuminate\Support\Collection;
use ReflectionClass;
use XbNz\AsusRouter\Data\Validators\ValidatorInterface;
use XbNz\AsusRouter\Data\Validators\WanIpListValidator;
use XbNz\AsusRouter\Exceptions\ValidatorNotFoundException;


class DataObject
{
    protected Collection $validators;

    public function __construct()
    {
        $this->validators = collect(app()->tagged('data-validators'));
    }

    protected function giveValidatorFor(string $useCase): ValidatorInterface
    {
        $validator = $this->validators->filter(function ($validator) use ($useCase) {
            if ($validator->supports() === strtolower($useCase)){
                return $validator;
            };
            return false;
        });

        if ($validator->isEmpty()){
            throw new ValidatorNotFoundException("No validator that supports {$useCase} was discovered");
        }

        return $validator->first();
    }
}