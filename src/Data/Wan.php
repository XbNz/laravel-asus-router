<?php


namespace XbNz\AsusRouter\Data;


class Wan
{
    protected string $output;
    public function __construct(protected $output){}

    public static function withIps(string $output)
    {
        return new static($output);
    }

}