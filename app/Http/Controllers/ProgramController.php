<?php

namespace App\Http\Controllers;

use App\Models\MachineLoader;

class ProgramController extends Controller
{
    public function overview(MachineLoader $machineLoader)
    {
        $data = [
            'packageStatuses' => $machineLoader->packageStatus,
            'profiles' => $machineLoader->getAllProfiles(),
            'machines' => $machineLoader->machines,
        ];
        return View('programs', $data);
    }
}
