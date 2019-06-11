<?php
//// DEV //// DEV //// DEV //// DEV //// DEV //// DEV //// DEV //// DEV //// DEV //// DEV
//$DocComments = new ReflectionClass('\DarlingCms\interfaces');
use DarlingCms\classes\FileSystem\DirectoryCrud;
use DarlingCms\classes\staticClasses\core\CoreValues;

$interfaceDir = new DirectoryCrud(CoreValues::getSiteRootDirPath() . '/core');

$IAccessControllerDocComments = new ReflectionClass('\DarlingCms\interfaces\accessControl\IAccessController');

// @todo move to class
define('INTERFACES', 1);
define('ABSTRACTIONS', 2);
define('CLASSES', 4);

var_dump(getSubDirPaths($interfaceDir));
var_dump(getSubDirPaths($interfaceDir, ['interfaces']));
var_dump(getSubDirPaths($interfaceDir, ['abstractions']));
var_dump(getSubDirPaths($interfaceDir, ['classes']));

/**
 * @param DirectoryCrud $directoryCrud
 * @param array $whitelistedDirs
 * @param int $filter
 * @return array
 */
function getSubDirPaths(DirectoryCrud $directoryCrud, $whitelistedDirs = array()): array
{
    $subDirPaths = array();
    $ignore = array('.', '..', '.DS_Store');
    $subDirs = array_diff(scandir($directoryCrud->getWorkingDirectoryPath()), $ignore);
    foreach ((empty($whitelistedDirs) === true ? $subDirs : $whitelistedDirs) as $dir) {
        /**
         * @var SplFileInfo $path
         */
        foreach ($directoryCrud->readDirectory($dir) as $path) {
            if (in_array($path->getFilename(), $ignore, true) === true) {
                continue;
            }
            array_push($subDirPaths, $path->getRealPath());
        }
    }
    return $subDirPaths;
}


/*
if ($filter & INTERFACES && $dir === 'interfaces') {
    array_push($namespaces, $path->getRealPath());
}
if ($filter & ABSTRACTIONS && $dir === 'abstractions') {
    array_push($namespaces, $path->getRealPath());
}
if ($filter & CLASSES && $dir === 'classes') {
    array_push($namespaces, $path->getRealPath());
}
*/
