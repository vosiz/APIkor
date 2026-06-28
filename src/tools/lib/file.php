<?php

namespace Apikor\Tools;

use \Vosiz\Enums\Enum;

class SpaceEnum extends Enum {

    /**
     * Abstract implementation
     */
    public static function Init() {

        $vals = [
            'master'    => 0x10,
            'user'      => 0x20,
        ];
        self::AddValues($vals);
    } 
}

/**
 * Check Apikor file existance
 * @param string $user_space path to user space dir (dir)
 * @param string $apikor_space path to apikor/master space dir (dir)
 * @param string $file filename
 * @param string $ext file extension (without ".")
 * @return string path to file
 */
function FILEOPS_Exists(string $user_space, string $apikor_space, string $file, string $ext = 'php') {

    try {

        $file .= '.'.$ext;
        $user_file = sprintf("%s/%s", $user_space, $file);
        $apikor_file = sprintf("%s/%s", $apikor_space, $file);

        if(file_exists($user_file)) { // user based - overrides master

            return [SpaceEnum::GetEnum('user'), $user_file];

        } else if(file_exists($apikor_file)) { // master based

            return [SpaceEnum::GetEnum('master'), $apikor_file];

        } else {

            throw new \Exceptionf("'$file' not found");
        }

    } catch (\Exception $exc) {

        throw $exc;
    }

}

/**
 * Path glue
 * @param string ...$paths path parts to combine
 * @return string full path
 */
function FILEOPS_PathCombine(...$paths) {
    return preg_replace('~[/\\\\]+~', DIRECTORY_SEPARATOR, join(DIRECTORY_SEPARATOR, array_map(fn($p) => trim($p, "/\\"), $paths)));
}

/**
 * Get all files on path
 * @param string $dir_path Directory path
 * @return array|false false if nothing to return
 */
function FILEOPS_GetFiles(string $dir_path, string $ext = '*') {

    $dir_path = rtrim($dir_path, '/\\');
    $dir_path = ltrim($dir_path, '/\\');

    $allowed = '/*';
    if($ext !== '*')
        $allowed = '/*.'.$ext;

    return glob($dir_path.$allowed);
} 