<?php
namespace GraphGuru;

use GraphGuru\Guru,
    GraphGuru\Config,
    Exception;

$src_dir = realpath(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'src');

require $src_dir . DIRECTORY_SEPARATOR . "guru.php";
require $src_dir . DIRECTORY_SEPARATOR . "config.php";

$config = Config::get_config();

if (isset($_GET['route']))
{
    if (!isset($_POST['access_token']) || empty($_POST['access_token']))
    {
        exit("Please provide an active and correct access token");
    }

    $guru = new Guru($config, $_POST['access_token']);

    switch ($_GET['route'])
    {
        case 'pages':
            $response = $guru->get_pages();
            break;
        case 'posts':
            if (!isset($_POST['page_id']) || empty($_POST['page_id']))
            {
                exit("Please provide a page_id");
            }
            $response = $guru->get_page_with_posts($_POST['page_id']);
            break;
        default:
            $response = '';
            break;
    }

    echo json_encode($response);
}