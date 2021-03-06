<?php
/**
 * @package Puzzle-DI
 * @copyright Copyright © 2015 Danny Smart
 */

namespace Downsider\PuzzleDI\Helper;

use Composer\Installer\InstallationManager;
use Composer\Repository\RepositoryInterface;
use Composer\Package\Package;

class PuzzleDataCollector
{

    protected $installationManager;

    public function __construct(InstallationManager $installationManager)
    {
        $this->installationManager = $installationManager;
    }

    public function collectData(RepositoryInterface $repo)
    {
        $puzzleData = [];
        foreach ($repo->getPackages() as $package) {
            /** @var Package $package */
            $extra = $package->getExtra();
            if (!empty($extra["downsider-puzzle-di"]) && is_array($extra["downsider-puzzle-di"])) {
                foreach ($extra["downsider-puzzle-di"] as $key => $config) {
                    if ($key == (string) (int) $key) {
                        continue;
                    }
                    if (!array_key_exists($key, $puzzleData)) {
                        $puzzleData[$key] = array();
                    }

                    $puzzleConfig = [
                        "name" => $package->getName(),
                        "path" => $this->installationManager->getInstallPath($package) . "/" .  $config["path"]
                    ];
                    if (!empty($config["alias"])) {
                        $puzzleConfig["alias"] = $config["alias"];
                    }
                    $puzzleData[$key][] = $puzzleConfig;
                }
            }
        }
        return $puzzleData;
    }

} 