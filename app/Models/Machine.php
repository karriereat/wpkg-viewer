<?php  // refactor me

namespace App\Models;

class Machine {
    public $hostname;
    public $status = null;
    public $profiles = array();
    public $lastUpdate = null;
    public $system = array(
        "os"            =>  array(
            "name"      =>  null,
            "sp"        =>  null,
            "version"   =>  null,
            "architecture"  =>  null,
        ),
        "domain"        =>  null,
    );
}
