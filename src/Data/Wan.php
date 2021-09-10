<?php


namespace XbNz\AsusRouter\Data;


use XbNz\AsusRouter\Data\Validators\ValidatorInterface;

class Wan extends DataObject
{

    public function getIpList(): \Illuminate\Support\Collection
    {
        return $this->validate();
    }

    public function setTerminalOutput(string $output): self
    {
        $this->rawOutput = $output;
        return $this;
    }
}