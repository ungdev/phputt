<?php

/*
 * This file is part of the Annotations library.
 *
 * Titouan Galopin <galopintitouan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

spl_autoload_register(function ($className) {
    $className = ltrim($className, '\\');

    if (0 != strpos($className, 'PhpUtt')) {
        return false;
    }

    $fileName = '';
    $namespace = '';

    if ($lastNsPos = strrpos($className, '\\')) {
        $namespace = substr($className, 0, $lastNsPos);
        $className = substr($className, $lastNsPos + 1);
        $fileName = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
    }

    $fileName = __DIR__ . DIRECTORY_SEPARATOR . $fileName . $className . '.php';

    if (is_file($fileName)) {
        require $fileName;
        return true;
    }

    return false;
});
