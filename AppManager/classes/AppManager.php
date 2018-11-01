<?php
/**
 * Created by PhpStorm.
 * User: sevidmusic
 * Date: 10/31/18
 * Time: 6:25 PM
 */

namespace Apps\AppManager\classes;


class AppManager
{
    public function enableApp(string $appName): bool
    {
        return true;
    }

    public function disableApp(string $appName): bool
    {
        return false;
    }

    /**
     * Creates an app with the specified $appName. This method will create a directory in the apps directory using the
     * specified $appName, and will create the following files under the created $appName directory:
     * - $appName/$appName.php
     * - $appName/AppConfig.php
     * - $appName/README.md
     *
     * @param string $appName The name of the app to create.
     * @return bool True if app was created successfully, false otherwise.
     */
    public function createApp(string $appName): bool
    {
        $appName = $this->filterAppName($appName);
        $appDirPath = $this->getAppDirPath($appName);
        $appPhpFilePath = $appDirPath . '/' . $appName . '.php';
        $appReadmeFilePath = $appDirPath . '/README.md';
        $appConfigFilePath = $appDirPath . '/AppConfig.php';
        $createMode = 0644;
        $status = array();
        if (!is_dir($appDirPath) === true && $this->reserved($appName) === false) {
            array_push($status, mkdir($appDirPath), $createMode);
            array_push($status, (file_put_contents($appPhpFilePath, '<?php' . PHP_EOL, LOCK_EX) > 0 ? true : false));
            chmod($appPhpFilePath, $createMode);
            array_push($status, touch($appReadmeFilePath));
            chmod($appReadmeFilePath, $createMode);
            array_push($status, (file_put_contents($appConfigFilePath, $this->generateAppConfig($appName), LOCK_EX) > 0 ? true : false));
            chmod($appConfigFilePath, $createMode);
            return !in_array(false, $status, true);
        }
        return false;
    }

    /**
     * Filters out any non alpha-numeric characters from the specified $appName.
     * @param string $appName The app name to filter.
     * @return string The filtered app name.
     */
    public function filterAppName(string $appName = ''): string
    {
        return str_replace(' ', '', ucwords(trim(preg_replace('/[^\w-]/', ' ', $appName))));
    }

    /**
     * Checks if a string is one of PHP's reserved words. This check is case-insensitive.
     * @param string $string The string to check.
     * @return bool True if $string matches is a reserved word in PHP, false otherwise.
     */
    public function reserved(string $string): bool
    {
        $keywords = array('__halt_compiler', 'abstract', 'and', 'array', 'as', 'break', 'callable', 'case', 'catch', 'class', 'clone', 'const', 'continue', 'declare', 'default', 'die', 'do', 'echo', 'else', 'elseif', 'empty', 'enddeclare', 'endfor', 'endforeach', 'endif', 'endswitch', 'endwhile', 'eval', 'exit', 'extends', 'final', 'for', 'foreach', 'function', 'global', 'goto', 'if', 'implements', 'include', 'include_once', 'instanceof', 'insteadof', 'interface', 'isset', 'list', 'namespace', 'new', 'or', 'print', 'private', 'protected', 'public', 'require', 'require_once', 'return', 'static', 'switch', 'throw', 'trait', 'try', 'unset', 'use', 'var', 'while', 'xor');
        $predefined_constants = array('__CLASS__', '__DIR__', '__FILE__', '__FUNCTION__', '__LINE__', '__METHOD__', '__NAMESPACE__', '__TRAIT__');
        $reserved = array_merge($keywords, $predefined_constants);
        return in_array(strtolower($string), $reserved, true);
    }

    /**
     * Generates the string of php code for a specified app's IAppConfig implementation.
     * @param string $appName The name of app to generate php code for.
     * @return string The generated php code.
     */
    private function generateAppConfig(string $appName): string
    {
        $appConfigTemplate = file_get_contents(__DIR__ . '/EmptyAppConfig.php');
        $correctedNamespace = str_replace('namespace Apps\appManager\classes;', 'namespace Apps\\' . $appName . ';', $appConfigTemplate);
        $correctedClass = str_replace('EmptyAppConfig', 'AppConfig', $correctedNamespace);
        $correctedAppName = str_replace('return \'\';', 'return \'' . $appName . '\';', $correctedClass);
        return $correctedAppName;
    }

    /**
     * Returns the path to the Darling Cms apps directory. Note: If the optional $appName parameter
     * is set then this method will return the path to the specified app.
     * @param string $appName (optional) The name of the app to whose path should be returned.
     * @return string The path to the Darling Cms apps directory, or, if the the $appName parameter was set,
     *                the path to the specified app.
     */
    public function getAppDirPath(string $appName = ''): string
    {
        if ($appName === '') {
            return str_replace('AppManager/classes', $appName, __DIR__);
        }
        return str_replace('AppManager/classes', '', __DIR__);
    }
}
