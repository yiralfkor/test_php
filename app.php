<?php

require_once 'vendor/autoload.php';
require_once 'config/config.php';


$tree = explode("/",str_replace(__DIR__,"",explode('?', $_SERVER['REQUEST_URI'])[0] ));

if($tree[1]==''){$path = 'index'; goto ND;}

if($tree[1]=='track'){$path = 'track'; goto ND;}

ND:;

if($path=='') $path='index';
$file_path = 'src/'.$bundle.'Bundle/controller/'.$path.'.php';


if (file_exists($file_path) && is_readable($file_path)) require_once $file_path;
else require_once $file_error;

?>
