<?php

namespace Apikor\Tools;

use \Vosiz\Enums\Enum;
use Vosiz\Utils\Io;

class SpaceEnum extends Enum {

    /**
     * Abstract implementation
     */
    public static function Init(): void {

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

    $file .= '.'.$ext;

    if($path = Io\File::Exists(Io\Path::Combine($user_space, $file)))
        return [SpaceEnum::GetEnum('user'), $path];

    if($path = Io\File::Exists(Io\Path::Combine($apikor_space, $file)))
        return [SpaceEnum::GetEnum('master'), $path];

    throw new \Exceptionf("'$file' not found");
}

/**
 * Path glue
 * @param string ...$paths path parts to combine
 * @return string full path
 */
function FILEOPS_PathCombine(...$paths) {
    return Io\Path::Combine(...$paths);
}

/**
 * Get all files on path
 * @param string $dir_path Directory path
 * @return array|false false if nothing to return
 */
function FILEOPS_GetFiles(string $dir_path, string $ext = '*') {

    $files = Io\Dir::GetFiles($dir_path);
    if($ext === '*') return $files;
    return array_values(array_filter($files, fn($f) => pathinfo($f, PATHINFO_EXTENSION) === $ext));
}