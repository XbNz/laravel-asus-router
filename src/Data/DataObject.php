<?php


namespace XbNz\AsusRouter\Data;

use Illuminate\Support\Collection;
use ReflectionClass;
use XbNz\AsusRouter\Data\Validators\ValidatorInterface;
use XbNz\AsusRouter\Data\Validators\WanValidator;


abstract class DataObject
{
    protected string $rawOutput;
    protected Collection $validated;
    protected Collection $validators;

    public function __construct(array $validators)
    {
        $this->validators = collect($validators);
    }

    abstract public function setTerminalOutput(string $output): self;

    protected function validate()
    {
        $child = new ReflectionClass($this);
        $validator = $this->validators->map(function ($validator) use ($child) {
            if ($validator->supports() === strtolower($child->getShortName())){
                return $validator;
            };
        })->first();

        return $validator->validate($this->rawOutput);
    }

}