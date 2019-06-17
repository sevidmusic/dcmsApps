<?php

use DarlingCms\classes\FileSystem\DirectoryCrud;
use DarlingCms\classes\staticClasses\utility\ArrayUtility;

function getDocComments(array $reflections): array
{
    $docComments = array();
    /**
     * @var ReflectionClass $reflection
     */
    foreach ($reflections as $reflection) {
        $docComments[$reflection->getName()] = formatForDisplay($reflection->getDocComment());
        $docComments[$reflection->getName()] .= '<h1>Methods:</h1>';
        /**
         * @var ReflectionMethod $reflectionMethod
         */
        foreach (getMethodStrings($reflection) as $methodString) {
            $docComments[$reflection->getName()] .= $methodString;
        }

    }
    return $docComments;
}

function getMethodStrings(ReflectionClass $reflectionClass): array
{
    $methodStrings = array();
    /**
     * @var ReflectionMethod $reflectionMethod
     */
    foreach ($reflectionClass->getMethods() as $reflectionMethod) {
        array_push(
            $methodStrings,
            sprintf(
                "<h4>%s(%s)%s</h4>",
                '<span style="color: #4da652;">' . $reflectionMethod->getName() . '</span>',
                getParamString($reflectionMethod),
                (empty($reflectionMethod->getReturnType()) === false ? ': ' . $reflectionMethod->getReturnType()->getName() : '')
            )
        );
    }
    return $methodStrings;
}

function getParamString(ReflectionMethod $reflectionMethod)
{
    $paramStr = '';
    $reflectionParameters = $reflectionMethod->getParameters();
    $count = count($reflectionParameters);
    $iterator = 0;
    /**
     * @var ReflectionParameter $reflectionParameter
     */
    foreach ($reflectionParameters as $reflectionParameter) {
        if (empty($reflectionParameter->getType()) === false) {
            $paramStr .= '<span style="color: #358bd5;">' . $reflectionParameter->getType()->getName() . ' </span>';
        }
        $paramStr .= sprintf(
            "<span style=\"color: #2a3070;\">\$%s</span>%s",
            $reflectionParameter->getName(),
            $iterator === ($count - 1) ? '' : ', '
        );
        $iterator++;
    }
    return $paramStr;
}

function formatForDisplay(string $docComment): string
{
    return str_replace(
        ['Note:', 'IMPORTANT:', ': ', 'Warning:', 'WARNING:'],
        ['<br><br>Note:', '<br><br>IMPORTANT:', ':<br><br>', '<br><br>Warning:', '<br><br>WARNING:'],
        str_replace(
            ['- '],
            ['<br>-'],
            str_replace(
                ['/*', '/**', '*/', '**/', '*'],
                '',
                $docComment
            )
        )
    );
}

function getReflections(array $namespaces): array
{
    $reflections = array();
    foreach ($namespaces as $namespace) {
        try {
            array_push($reflections, new ReflectionClass($namespace));
        } catch (ReflectionException $e) {
            error_log(sprintf('Darling Cms Documentation Error: Failed to generate Reflection for namespace %s', $namespace));
        }
    }
    return $reflections;
}

function convertPathsToNamespaces(array $array): array
{
    $namespaces = array();
    foreach ($array as $filePath) {
        $namespace = str_replace(['/', '.php'], ['\\', ''], $filePath);
        $ns = explode('\\', $namespace);
        $namespace = ArrayUtility::splitArrayAtValue($ns, 'core')[1];
        array_push($namespaces, '\\DarlingCms\\' . implode('\\', $namespace));
    }
    return $namespaces;
}

/**
 * @param DirectoryCrud $directoryCrud
 * @return array
 */
function getSubPhpFilePaths(DirectoryCrud $directoryCrud): array
{
    $subFilePaths = array();
    foreach (getSubDirPaths($directoryCrud) as $corePath) {
        $glob = glob($corePath . '/*.php');
        $subFilePaths = array_merge($subFilePaths, $glob);
    }
    return array_unique($subFilePaths, SORT_STRING);
}

/**
 * Returns an array of sub directory paths under the DirectoryCrud implementation instance's
 * working directory.
 *
 * Note: This method only returns paths for sub directories, files and other non-directory types
 *       will be excluded.
 *
 * @param DirectoryCrud $directoryCrud DirectoryCrud implementation instance for the working parent directory.
 *
 * @param array $whitelistedDirs Array of the names of specific directories whose paths should be returned,
 *                                if set, any directories not specified in this array will be excluded.
 *
 * @return array An array of sub directory paths that exist under the specified DirectoryCrud implementation
 *               instance's working directory.
 */
function getSubDirPaths(DirectoryCrud $directoryCrud, $whitelistedDirs = array()): array
{
    $subDirPaths = array();
    $ignore = array('.', '..', '.DS_Store');
    $subDirs = array_diff(scandir($directoryCrud->getWorkingDirectoryPath()), $ignore);
    foreach ((empty($whitelistedDirs) === true ? $subDirs : $whitelistedDirs) as $dir) {
        // ignore non-directories
        if (is_dir($directoryCrud->getWorkingDirectoryPath() . $dir) === false) {
            continue;
        }
        /**
         * @var SplFileInfo $path
         */
        foreach ($directoryCrud->readDirectory($dir) as $path) {
            // ignore specified directories and non-directories
            if (in_array($path->getFilename(), $ignore, true) === true || $path->isDir() === false) {
                continue;
            }
            array_push($subDirPaths, $path->getRealPath());
        }
    }
    return $subDirPaths;
}
