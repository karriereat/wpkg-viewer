<?php // refactor me

namespace App\Models;

class Machine
{
    public $hostname;
    public $status = null;
    public $profiles = [];
    public $lastUpdate = null;
    public $system = [
        "os" => [
            "name" => null,
            "sp" => null,
            "version" => null,
            "architecture" => null,
        ],
        "domain" => null,
    ];
}
