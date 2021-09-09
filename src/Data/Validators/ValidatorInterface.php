<?php


namespace XbNz\AsusRouter\Data\Validators;


use Illuminate\Support\Collection;
use XbNz\AsusRouter\Data\DataObject;

interface ValidatorInterface
{
    public function validate(DataObject $data): Collection|bool;
}