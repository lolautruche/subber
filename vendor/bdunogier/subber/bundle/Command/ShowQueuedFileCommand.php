<?php
namespace BD\SubberBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ProcessQueuedFileCommand extends ContainerAwareCommand
{
    public function configure()
    {
        $this->setName( 'subber:show-queued-file' );
        $this->setDescription( 'Shows a queued file information' );
        $this->addArgument( 'release-file', InputArgument::REQUIRED, "The local release file path" );
    }

    public function execute( InputInterface $input, OutputInterface $output )
    {
        $factory = $this->getContainer()->get( 'bd_subber.subtitled_episode_release_factory' );
        $release = $factory->buildFromlocalReleasePath( $input->getArgument( 'release-file' ) );
        print_r( $release );
    }
}