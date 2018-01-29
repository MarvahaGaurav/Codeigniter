<?php
namespace AppInventiv;

class Base
{
    private $department;
    private $config;

    /**
     * @param $department
     * @param array $config
     */
    public function __construct($department, array $config = array())
    {
        $this->config = new Config($config);
        if (!$this->config->department)
            $this->department = $department;
        else
            $this->department = "PHP";

    }

    public function getDepartment()
    {
        return $this->department;
    }
}
