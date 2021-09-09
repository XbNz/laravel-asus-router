<?php


namespace XbNz\AsusRouter\Data;

use Illuminate\Support\Collection;
use XbNz\AsusRouter\Data\Validators\ValidatorInterface;
use XbNz\AsusRouter\Data\Validators\WanValidator;


abstract class DataObject
{
    protected string $callingClass;
    public string $output;
    protected Collection $validated;

    public function __construct()
    {
        $callingClass = new \ReflectionClass($this);
        $this->callingClass = $callingClass->getShortName();
        $this->validate();
    }

    protected function validate()
    {
        $validatorName = $this->callingClass . 'Validator';
        $namespace = 'XbNz\AsusRouter\Data\Validators';
        $classFqn = $namespace . "\\" . $validatorName;
        $this->validated = (new $classFqn())->validate($this);
    }


    abstract public static function withTerminalOutput(string $output): self;
}