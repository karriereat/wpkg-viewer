<?php

namespace App\Http\Controllers;

use App\Models\MachineLoader;

class ProfileController extends Controller
{
    public function overview(MachineLoader $machineLoader)
    {
        $profiles = $machineLoader->getAllProfiles();
        return View('profiles', ['profiles' => $profiles]);
    }

    public function detail($profileNameParam, MachineLoader $machineLoader)
    {
        $profiles = $machineLoader->getAllProfiles();

        $machineFound = false;
        foreach($profiles as $profilesName => $profile) {
            if(strtolower($profilesName) == $profileNameParam) {
                $machineFound = true;
                break;
            }
        }

        if (!$machineFound) {
            return redirect(url('profiles'));
        }

        return View('profile', ['profile' => $profile, 'profilesName' => $profilesName]);
    }
}
