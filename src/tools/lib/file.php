<?php

namespace Apikor\Tools;

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
    $user_file = sprintf("%s/%s", $user_space, $file);
    $apikor_file = sprintf("%s/%s", $apikor_space, $file);

    if(file_exists($user_file)) { // user base - overrides master

        return $user_file;

    } else if(file_exists($apikor_file)) { // master base

        return $apikor_file;

    } else {

        throw new \Exceptionf("'$file' not found");
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