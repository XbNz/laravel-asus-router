<?php


namespace XbNz\AsusRouter\Data;


use XbNz\AsusRouter\Data\Validators\ValidatorInterface;

class Wan extends DataObject
{
    public function __construct(public string $output)
    {
        parent::__construct();
    }

    public function getIpList(): \Illuminate\Support\Collection
    {
        return $this->validated;
    }

    public static function withTerminalOutput(string $output): self
    {
        return new static($output);
    }
}