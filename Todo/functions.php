<?php
/**
 * Recursively scans the specified directory and returns the paths of any items that conform to the specified
 * parameters in a multidimensional array.
 * @param string $dir The directory to scan.
 * @param array $filter If the $whitelist parameter is set to false, any items that match a value in this array will
 *                      be ignored. If the $whitelist parameter is set to true, only items that match a value in this
 *                      array will be included.
 * @param bool $whitelist (Optional) If set to true, then only items whose filename or extension matches one of the
 *                        values in the $filter array wll be included. If set to false, items whose filename or
 *                        extension matches one of the items in the $filter array will be ignored.
 * @return array Array of paths to the items in the specified directory. Note: If $whitelist is set to true
 *               then only items whose filename or extension match a value in the $filter array will be returned,
 *               otherwise items whose filename or extension match a value in the $filter array will be ignored.
 * @WARNING: At the moment this method is case sensitive! So "fileName" and "filename" are not the same!
 * @todo: FSgetPaths() should accommodate upper and lower case versions of extensions. right now the extensions "Php" and "php" do not match, they should.
 */
function FSgetPaths($dir, $filter = array(), bool $whitelist = false)
{
    $alwaysIgnore = array('.DS_Store', '.idea', '.git', '.gitignore', 'vendor', 'composer.lock');
    $filter = ($whitelist === true ? $filter : array_merge($filter, $alwaysIgnore));
    $paths = array();
    $iter = new FilesystemIterator($dir, FilesystemIterator::SKIP_DOTS);
    foreach ($iter as $file) {
        if ($file->isDir() && !in_array($file->getFilename(), $alwaysIgnore, true)) {
            $paths[$file->getFilename()] = FSgetPaths($file->getPathname(), $filter, $whitelist);
        } else {
            if ($whitelist === true && (in_array($file->getFilename(), $filter, true) || in_array('.' . $file->getExtension(), $filter, true)) === false) {
                continue;
            }
            if ($whitelist === false && (in_array($file->getFilename(), $filter, true) || in_array('.' . $file->getExtension(), $filter, true)) === true) {
                continue;
            }
            $paths[$file->getFilename()] = $file->getRealPath();
        }
    }
    return array_filter($paths);
}

function flattenArray(array $array, bool $preserveKeys = false): array
{
    $flatArray = array();
    $recursiveIteratorIterator = new RecursiveIteratorIterator(new RecursiveArrayIterator($array));
    $keyIterator = 0;
    foreach ($recursiveIteratorIterator as $item) {
        if ($preserveKeys === true) {
            $key = getIteratedItemKey($recursiveIteratorIterator) . $keyIterator;
            /**
             * If item with index $key already exists, append $keyIterator to $key to insure uniqueness and to prevent
             * overwrite of existing items, otherwise use $key as index.
             */
            $flatArray[(isset($flatArray[$key]) === true ? $key . $keyIterator : $key)] = $item;
            $keyIterator++;
            continue;
        }
        /**
         * Preserve keys set to false, index items numerically.
         */
        array_push($flatArray, $item);
    }
    return $flatArray;
}

/**
 * Get the key of the current RecursiveIteratorIterator item in a loop.
 * WARNING: This method must be called from within the loop to work as intended.
 * i.e.,
 * // good
 * $recursiveIteratorIterator = new RecursiveIteratorIterator(new RecursiveArrayIterator($array));
 * foreach($recursiveIteratorIterator as $key => $value) { var_dump(getIteratedItemKey($recursiveIteratorIterator)); // code... }
 * // bad
 * $recursiveIteratorIterator = new RecursiveIteratorIterator(new RecursiveArrayIterator($array));
 * var_dump(getIteratedItemKey($recursiveIteratorIterator)); // BAD! Called outside of loop!
 * foreach($recursiveIteratorIterator as $key => $value) { // code... }
 * @param RecursiveIteratorIterator $iterator
 * @param mixed $level getIteratedItemKey($recursiveIteratorIterator, $recursiveIteratorIterator->getDepth())
 * @see https://stackoverflow.com/questions/16855211/php-recursive-iterator-parent-key-of-current-array-iteration
 */
function getIteratedItemKey(RecursiveIteratorIterator $iterator, int $level = null): string
{
    if (empty($level) === false) {
        return $iterator->getSubIterator($level)->key();
    }
    return $iterator->getSubIterator()->key();
}

function getIteratedItemKeys(RecursiveIteratorIterator $iterator)
{
    foreach ($iterator as $key => $value) {
        // loop through the subIterators...
        $keys = array();
        // in this case i skip the grand parent (numeric array)
        for ($i = $iterator->getDepth() - 1; $i > 0; $i--) {
            $keys[] = $iterator->getSubIterator($i)->key();
        }
        $keys[] = $key;
    }
    //var_dump($keys);
}

/**
 * Get the specified files "todos"
 * @param string $sourceFilePath The full path to the source file.
 * @return array Array of todos found in the specified file.
 */
function getTodos(string $sourceFilePath): array
{
    $testFile = file(realpath($sourceFilePath));
    $todos = array();
    $todoTypeIncrementer = 1;
    foreach ($testFile as $lineNumber => $line) {
        $extractedValues = explode('@', $line);
        foreach ($extractedValues as $extractedValue) {
            if (substr($extractedValue, 0, 4) === 'todo') {
                $todoTxt = substr($extractedValue, 5) . "<textarea class='todo-editable-todo-text'>" . str_replace(array('<br>', '<br/>', '*EOL*'), array(PHP_EOL, PHP_EOL, '&#10;'), substr($extractedValue, 5)) . "</textarea>";
                $important = (substr($todoTxt, 0, 1) === '!' ? true : false);
                $active = stringHasString('*ACTIVE*', $todoTxt);
                $todoString = '<div class="' . ($active === true ? 'todo-active' : 'todo-inactive') . '"><span class="todo-from-file-txt' . ($important === true ? ' todo-important-from-file-txt' : '') . '">From <span class="todo-source-file-path">' . $sourceFilePath . '</span><span class="todo-line-text">(line ' . bcadd($lineNumber, '1', 0) . ')</span>: </span><br><span class="todo-txt' . ($important === true ? ' todo-important-txt' : '') . '">' . ($important === true ? substr($todoTxt, 1) : $todoTxt) . '</span></div>';
                // NOTE: Before indexes where set at top level, i.e. $todo[(check for existing...)], even though a check was made to prevent overwriting existing items, some items still seemed to be left out, to further insure all items are included items are now indexed at the child level, $todo[][(...)]. The check for existing items is still performed, but now at the child level.
                $todos[][(
                $active === true && $important === true
                    ? '*ACTIVE*IMPORTANT*' . $todoTypeIncrementer
                    : (
                $active === true
                    ? '*ACTIVE*' . $todoTypeIncrementer
                    : (
                $important === true
                    ? '*IMPORTANT*'
                    : $todoTypeIncrementer
                )
                )
                )] = str_replace(array('*EOL*', '*ACTIVE*'), array('<br><br>', ''), $todoString);
                $todoTypeIncrementer++;
            }
        }
    }
    return $todos;
}

function stringHasString(string $needle, string $haystack): bool
{
    if (strpos($haystack, $needle) !== false) {
        return true;
    }
    return false;
}
