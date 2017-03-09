<?php

namespace SixtyNine\CloudBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Yaml\Yaml;

class LoadDefaultPalettesCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('sn:palettes:load-default')
            ->setDescription('...')
            ->addArgument('argument', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $repo = $this->getContainer()->get('doctrine.orm.entity_manager')->getRepository('SixtyNineCloudBundle:Palette');
        $yaml = Yaml::parse(__DIR__ . '/../Resources/config/default-palettes.yml');
        foreach ($yaml['palettes'] as $name => $colors) {
            $repo->importPalette($name, $colors);
        }
    }

}
