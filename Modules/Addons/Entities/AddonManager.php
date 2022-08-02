<?php

namespace Modules\Addons\Entities;

use Modules\Addons\Entities\Addon;
use Artisan, File, ZipArchive;
use Illuminate\Http\Request;

class AddonManager
{
    /**
     * upload
     *
     * @param  request $addonZip
     * @return collection
     */
    public static function upload($addonZip)
    {
        if (!class_exists('ZipArchive')) {
            return false;
        }

        $zipped_file_name = pathinfo($addonZip->getClientOriginalName(), PATHINFO_FILENAME);
        
        $zip = new ZipArchive;
        $res = $zip->open($addonZip);

        if ($res === true) {
            if(!File::isDirectory(base_path('temp'))){
                File::makeDirectory(base_path('temp'), config('addons.file_permission'), true, true);
            }
            (new \Illuminate\Filesystem\Filesystem)->cleanDirectory(base_path('temp'));

            $res = $zip->extractTo(base_path('temp'));
            $zip->close();
        }
        $tempModulePath = base_path('temp') .'/'. $zipped_file_name . '/';
        $tempFilePaths = [
            'Config/config.php',
            'Providers/' . $zipped_file_name . 'ServiceProvider.php',
            'Providers/RouteServiceProvider.php',
            'Routes/api.php',
            'Routes/web.php',
            'module.json'
        ];

        foreach ($tempFilePaths as $tempFilePath) {
            if (! file_exists($tempModulePath . $tempFilePath)) {
                File::deleteDirectory(base_path('temp/' . $zipped_file_name));
                return false;
            }
            if ($tempFilePath == 'module.json') {
                $moduleJson = file_get_contents($tempModulePath . $tempFilePath);
                $moduleJsonArray = !empty($moduleJson) ? json_decode($moduleJson, true) : [];
                if (!array_key_exists('name', $moduleJsonArray) && !array_key_exists('item_id', $moduleJsonArray)) {
                    File::deleteDirectory(base_path('temp/' . $zipped_file_name));
                    return false;
                }
            }
        }

        File::copyDirectory(base_path('temp'), base_path('Modules'));
        File::deleteDirectory(base_path('temp/' .  $zipped_file_name));

        return Addon::findOrFail($zipped_file_name);
    }
    
    /**
     * migrateAndSedd
     *
     * @param  mixed $name
     * @return void
     */
    public static function migrateAndSeed($name)
    {
        Artisan::call('module:migrate-rollback ' . $name);
        Artisan::call('module:migrate ' . $name);
        Artisan::call('module:seed ' . $name);
    }
}
