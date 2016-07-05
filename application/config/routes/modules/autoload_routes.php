<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 6/16/2016
 * Time: 8:36 AM
 *
 * autoload route from modules
 */

$dir = __DIR__;
$listDir = scandir($dir);
if($listDir) {
    foreach ($listDir as $item) {
        if(is_dir($dir . '/'. $item) && file_exists($dir . '/' . $item . '/routes.php')) {
            require_once $dir . '/' . $item . '/routes.php';
        }
    }
}