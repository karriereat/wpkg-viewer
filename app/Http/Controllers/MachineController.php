<?php

namespace App\Http\Controllers;

use App\Models\MachineLoader;

class MachineController extends Controller
{
    public function overview(MachineLoader $machineLoader)
    {
        $data = [
            'machines' => $machineLoader->machines,
            'profiles' => $machineLoader->getAllProfiles(),
        ];
        return View('machines', $data);
    }

    public function detail($hostenameParam, MachineLoader $machineLoader)
    {
        $machines = $machineLoader->machines;

        $machineFound = false;
        foreach($machines as $hostname => $machine) {
            if(strtolower($hostname) == $hostenameParam) {
                $machineFound = true;
                break;
            }
        }
        if (!$machineFound) {
           return abort(404);
        }

        return View('machine', ['machine' => $machine]);
    }
}
