<?php

// refactor me!

namespace App\Models;

class MachineLoader
{
    private $basePath;
    private $pathToHostsXml;

    private $architectures = [
        'x86' => 32,
        'x64' => 64,
    ];

    private $wildcardChars = '.+';
    private $packages = null;
    private $htmlArrow = ' ➡️ ';

    private $machineStatus = [
        0 => 'ok',
        1 => 'host.xml not set',
        2 => 'no information',
    ];

    public $packageStatus = [
        'INSTALLED' => [
            'index' => 0,
            'desc' => 'installed',
            'color' => '#FFF',
        ],
        'TO_INSTALL' => [
            'index' => 1,
            'desc' => 'to be installed',
            'color' => '#FFCE89',
        ],
        'TO_UPDATE' => [
            'index' => 2,
            'desc' => 'to be updated',
            'color' => '#ACFFA6',
        ],
        'UNINSTALL' => [
            'index' => 3,
            'desc' => 'to be uninstalled',
            'color' => '#FCA9A9',
        ],
        'ALWAYS' => [
            'index' => 4,
            'desc' => 'on every start',
            'color' => '#FFF',
        ],
    ];
    public $machines = [];

    public function __construct()
    {
        $this->setEnvVars();
        $this->getAllMachines();
    }

    public function setEnvVars()
    {
        $this->basePath = env('WPKG_VIEWER_BASE_PATH', 'test-files');
        $this->pathToHostsXml = env('WPKG_VIEWER_PATH_TO_HOSTS_XML', 'hostxml/');
    }

    public function getAllProfiles()
    {
        $profiles = [];
        foreach ($this->machines as $machine) {
            foreach ($machine->profiles as $profileName => $profile) {
                if (!array_key_exists($profileName, $profiles)) {
                    $profiles[$profileName]['packages'] = [];
                    $profiles[$profileName]['amount'] = 1;
                } else {
                    $profiles[$profileName]['amount']++;
                }
                foreach ($profile as $packageName => $package) {
                    if (!array_key_exists($packageName, $profiles[$profileName]['packages'])) {
                        $profiles[$profileName]['packages'][$packageName] = [];
                        $profiles[$profileName]['packages'][$packageName][$package['status']['desc']] = [];
                        $profiles[$profileName]['packages'][$packageName][$package['status']['desc']][] = ['hostname' => $machine->hostname, 'lastUpdate' => $machine->lastUpdate];
                    } else {
                        if (!array_key_exists($package['status']['desc'], $profiles[$profileName]['packages'][$packageName])) {
                            $profiles[$profileName]['packages'][$packageName][$package['status']['desc']] = [];
                            $profiles[$profileName]['packages'][$packageName][$package['status']['desc']][] = ['hostname' => $machine->hostname, 'lastUpdate' => $machine->lastUpdate];
                        } else {
                            $profiles[$profileName]['packages'][$packageName][$package['status']['desc']][] = ['hostname' => $machine->hostname, 'lastUpdate' => $machine->lastUpdate];
                        }
                    }
                }
            }
        }

        return $profiles;
    }

    private function getAllMachines()
    {
        $this->packages = $this->loadFile('packages.xml');

        $this->getHosts();
        $this->getProfiles();
        foreach ($this->machines as $hostname => $machine) {
            $this->getHost($hostname);
        }
        $this->finalize();

        return $this->machines;
    }

    private function getPackageVersionById($id)
    {
        foreach ($this->packages as $package) {
            if ($id == (string) $package->attributes()->id) {
                return $this->_getVersion($package);
            }
        }

        return false;
    }

    private function getHosts()
    {
        $hostsFromFile = $this->loadFile('hosts.xml');
        $hostsFromDir = $this->getHostsFromFolder('hostxml/');

        foreach ($hostsFromFile->host as $host) {
            if (strstr($host->attributes()->name, $this->wildcardChars) !== false) {
                foreach ($hostsFromDir as $hostFromDir) {
                    if (strstr(strtolower($hostFromDir), strtolower(str_replace($this->wildcardChars, '', $host->attributes()->name)))) {
                        $this->createNewMachine($host, $hostFromDir);
                    }
                }
            } else {
                $this->createNewMachine($host);
            }
        }
    }

    private function createNewMachine($host, $name = false)
    {
        $machine = new Machine();
        if ($name != false) {
            $machine->hostname = $name;
        } else {
            $machine->hostname = (string) $host->attributes()->name;
        }
        $machine->profiles[(string) $host->attributes()->{'profile-id'}] = [];
        $machine->status = $this->machineStatus[1];
        if (isset($host->profile)) {
            for ($i = 0; $i < count($host->profile); $i++) {
                $machine->profiles[(string) $host->profile[$i]->attributes()->id] = [];
            }
        }
        $this->machines[$machine->hostname] = $machine;
    }

    private function getHostsFromFolder($folder)
    {
        $fileList = $this->getFolderFiles($folder, 'xml');
        $files = [];
        foreach ($fileList as $file) {
            $files[] = $file['filename'];
        }

        return $files;
    }

    private function getProfiles()
    {
        $profiles = $this->loadFile('profiles.xml');
        foreach ($this->machines as $machine) {
            foreach ($profiles as $profile) {
                foreach ($machine->profiles as $machineProfiles) {
                    $key = (string) $profile->attributes()->id;
                    if (array_key_exists($key, $machine->profiles)) {
                        for ($j = 0; $j < count($profile->package); $j++) {
                            if (!in_array((string) $profile->package[$j]->attributes()->{'package-id'}, $machine->profiles[$key])) {
                                $machine->profiles[$key][(string) $profile->package[$j]->attributes()->{'package-id'}] = [];
                            }
                        }
                    }
                }
            }
        }
    }

    private function getHost($hostname)
    {
        $filePath = sprintf('%s%s.xml', $this->pathToHostsXml, $hostname);
        $host = $this->loadFile($filePath);
        if ($host) {
            $machine = $this->machines[$hostname];
            $machine->lastUpdate = $this->getModificationTimeOfFile($filePath);
            $machine->hostname = (string) $host->attributes()->hostname;
            $os = explode(', ', (string) $host->attributes()->os);
            $machine->system['os']['architecture'] = $this->architectures[(string) $host->attributes()->architecture];
            $machine->system['os']['name'] = $os[0];
            $machine->system['os']['sp'] = intval(str_replace('sp', '', $os[2]));
            $machine->system['os']['version'] = $os[3];
            $machine->system['domain'] = (string) $host->attributes()->domainname;
            if ($machine->system['os']['architecture'] == null && $machine->system['domain'] == null) {
                $machine->status = $this->machineStatus[2];
            } else {
                $machine->status = $this->machineStatus[0];
            }
            foreach ($machine->profiles as $profileKey => $profiles) {
                foreach ($host->package as $package) {
                    // only add version and status if this program is in package
                    if (array_key_exists((string) $package->attributes()->id, $machine->profiles[$profileKey])) {
                        $status = $this->_getCurrentStatus($host, $package, $profiles, $machine->profiles);
                        $currentVersion = $this->_getVersion($package);

                        if ($status['status'] == $this->packageStatus['TO_UPDATE']) {
                            $version = sprintf('%s %s %s', $currentVersion, $this->htmlArrow, $status['newVersion']);
                        } else {
                            $version = $currentVersion;
                        }

                        $machine->profiles[$profileKey][(string) $package->attributes()->id]['status'] = $status['status'];
                        $machine->profiles[$profileKey][(string) $package->attributes()->id]['version'] = $version;

                        $machine->profiles[$profileKey][(string) $package->attributes()->id]['name'] = (string) $package->attributes()->name;
                    } else {
                        // if package is in host.xml and not in packages => to be uninstalled
                        if (!$this->multiKeyExists((string) $package->attributes()->id, $machine->profiles)) {
                            $machine->profiles[$profileKey][(string) $package->attributes()->id]['status'] = $this->packageStatus['UNINSTALL'];
                        }
                    }
                }
            }
        } else {
            return false;
        }
    }

    private function _getVersion($package)
    {
        $revision = (string) $package->attributes()->revision;
        if (!strlen($revision)) {
            return ''; // TODO fix this!
        }
        if ($revision[0] == '%' && substr($revision, -1) == '%') {
            foreach ($package->variable as $packageContent) {
                $revision = str_replace('%', '', $revision);
                if ($revision == (string) $packageContent->attributes()->name) {
                    return (string) $packageContent->attributes()->value;
                }
            }
        } else {
            return (string) $revision;
        }
    }

    private function _getCurrentStatus($host, $package, $profile, $returnNewVersion = true)
    {
        $packageVersion = (string) $this->getPackageVersionById($package->attributes()->id);
        $hostVersion = (string) $this->_getVersion($package);
        $status = null;
        if ($packageVersion == $hostVersion) {
            $status = $this->packageStatus['INSTALLED'];
        }

        if ((string) $package->attributes()->execute == 'always') {
            $status = $this->packageStatus['ALWAYS'];
        }

        if ($package->attributes()->id == 'chrome') {
            $status = $this->packageStatus['INSTALLED'];
        }

        if ($status == null) {
            $status = $this->packageStatus['TO_UPDATE'];
        }

        if ($returnNewVersion == true) {
            return [
                'newVersion' => $packageVersion,
                'status' => $status,
            ];
        }

        return $status;
    }

    private function finalize()
    {
        foreach ($this->machines as $hostname => $machine) {
            foreach ($machine->profiles as $profileName => $profile) {
                foreach ($profile as $packageName => $package) {
                    if (count($package) == 0) {
                        $newVersion = $this->getPackageVersionById($packageName);
                        $version = sprintf('%s %s', $this->htmlArrow, $newVersion);
                        $this->machines[$hostname]->profiles[$profileName][$packageName]['status'] = $this->packageStatus['TO_INSTALL'];
                        $this->machines[$hostname]->profiles[$profileName][$packageName]['version'] = $version;
                    }
                }
            }
        }
    }

    public function getFolderFiles($path, $extension = false)
    {
        $path = $this->basePath.$path;
        $directory = scandir($path);
        $files = [];

        foreach ($directory as $file) {
            $filePath = $path.$file;
            if (file_exists($filePath)) {
                $fileInfo = pathinfo($file);
                if ($extension !== false) {
                    if (array_key_exists('extension', $fileInfo) && $fileInfo['extension'] == $extension) {
                        $files[] = $fileInfo;
                    }
                } else {
                    $files[] = $fileInfo;
                }
            }
        }

        return $files;
    }

    private function loadFile($path)
    {
        $path = sprintf('%s%s', $this->basePath, $path);
        if (file_exists($path)) {
            $document = @simplexml_load_file($path);
            if ($document == false) {
                return false;
            }

            return $document;
        } else {
            return false;
        }
    }

    private function getModificationTimeOfFile($path)
    {
        $path = sprintf('%s%s', $this->basePath, $path);
        $path = realpath($path);
        if (file_exists($path)) {
            $time = filemtime($path);

            return $time;
        } else {
            return false;
        }
    }

    /* from http://stackoverflow.com/a/19420866 */
    private function multiKeyExists($key, array $array)
    {
        if (array_key_exists($key, $array)) {
            return true;
        }
        foreach ($array as $k => $v) {
            if (!is_array($v)) {
                continue;
            }
            if (array_key_exists($key, $v)) {
                return true;
            }
        }

        return false;
    }
}
