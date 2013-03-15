<?php
// necessary requirements to get config
$src_dir = realpath(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'src');
require_once $src_dir . DIRECTORY_SEPARATOR . "config.php";

use GraphGuru\Config;
$config = Config::get_config();

// load up our route as an array
$route_parts = isset($_GET['route']) ? explode('/', $_GET['route']) : array();
$route_parts[0] = isset($route_parts[0]) ? $route_parts[0] : 'pages';


// Require our shared header
require_once "inc/header.php";

switch ($route_parts[0])
{
    case 'posts':
        require_once 'page_posts.php';
        break;
    case 'guide':
        require_once 'guide.php';
        break;
    case 'pages':
        require_once 'page_select.php';
        break;
    default:
        break;
}

require_once 'inc/footer.php';