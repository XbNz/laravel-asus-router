<?php


namespace XbNz\AsusRouter\Data;

use Illuminate\Support\Collection;
use ReflectionClass;
use XbNz\AsusRouter\Data\Validators\ValidatorInterface;
use XbNz\AsusRouter\Data\Validators\WanIpListValidator;
use XbNz\AsusRouter\Exceptions\ValidatorNotFoundException;


abstract class DataObject
{
    protected Collection $validators;

    public function __construct(array $validators)
    {
        $this->validators = collect($validators);
    }

    public function giveValidatorFor(string $useCase): ValidatorInterface
    {
        return $this->validators->map(function ($validator) use ($useCase) {
            if ($validator->supports() === strtolower($useCase)){
                return $validator;
            };
            throw new ValidatorNotFoundException("No validator that supports {$useCase} was discovered");
        })->first();
    }
}