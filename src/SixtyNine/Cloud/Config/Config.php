<?php


namespace SixtyNine\Cloud\Config;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Yaml\Yaml;


class Config
{
    /** @var array */
    protected $config;

    public function __construct($configFilePaths = array())
    {
        if (!is_array($configFilePaths)) {
            throw new \InvalidArgumentException('Invalid array of file path');
        }

        $configFilePaths = array_merge(
            $configFilePaths,
            array(__DIR__ . '/../Resources/config')
        );

        $locator = new FileLocator($configFilePaths);
        $resource = $locator->locate('config.yml', null, false);

        $file = reset($resource);
        $configValues = Yaml::parse(file_get_contents($file));


        $processor = new Processor();

        $processedConfiguration = $processor->processConfiguration(
            new CloudConfig(),
            $configValues
        );

        $this->config = $processedConfiguration;
    }

    public function get($key)
    {
        if (!array_key_exists($key, $this->config)) {
            throw new \InvalidArgumentException($key . ' key not found in config');
        }

        return $this->config[$key];
    }
}
