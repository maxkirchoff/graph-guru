<?php
namespace GraphGuru;
use DomainException;

/**
 * Class for config management
 */
class Config
{
    /**
     * Getter for the config vars
     *
     * @return array
     * @throws \DomainException
     */
    static public function get_config()
    {
        // establish src dir
        $src_dir = realpath(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'src');
        // establish conf dir
        $config_file_path = $src_dir . DIRECTORY_SEPARATOR . 'conf/config.ini';

        // Check for conf existence
        if (! file_exists($config_file_path))
        {
            // TODO: something better
            throw new DomainException("ERROR! You must have a config.ini file to continue.");
        }

        // parse our conf ini
        $config = parse_ini_file($config_file_path, TRUE);

        // Check for empty fields (all are required)
        foreach ($config as $field)
        {
            if (empty($field))
            {
                // TODO: something better
                throw new DomainException("ERROR! You must provide a value for all config fields.");
            }
        }

        return $config;
    }
}
