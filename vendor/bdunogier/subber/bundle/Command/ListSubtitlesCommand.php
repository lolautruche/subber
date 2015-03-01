<?php
namespace BD\SubberBundle\Command;

use BD\Subber\Election\Ballot\BasicBallot;
use BD\Subber\Subtitles\Subtitle;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Lists subtitles for a video file
 */
class ListSubtitlesCommand extends ContainerAwareCommand
{
    public function configure()
    {
        $this->setName( 'subber:list-subtitles' );
        $this->addArgument( 'video-file',         InputArgument::REQUIRED, "The path to the video file the subtitle subtitles must be listed for" );
        $this->addArgument( 'downloaded-release', InputArgument::REQUIRED, "The name of the downloaded release file" );
    }

    public function execute( InputInterface $input, OutputInterface $output )
    {
        $printSubtitleCallback = function ( Subtitle $subtitle ) use ( $output ) {
            $output->writeln( "$subtitle->name ($subtitle->language, $subtitle->url)" );
        };

        $videoFile = $input->getArgument( 'video-file' );
        $downloadedRelease = $input->getArgument( 'downloaded-release' );

        $output->writeln( "Listing subtitles for $videoFile" );

        $factory = $this->getContainer()->get( 'bd_subber.downloaded_episode_subtitle_collection_factory' );
        $collection = $factory->getCollection( $downloadedRelease );

        $acceptableSubtitles = $collection->getAcceptableSubtitles();
        if ( count( $acceptableSubtitles ) )
        {
            $output->writeln( "" );
            $output->writeln( "Best subtitle:" );
            $printSubtitleCallback( $acceptableSubtitles[0] );

            $output->writeln( "" );
            $output->writeln( "Acceptable subtitles (" . count( $collection->getAcceptableSubtitles() ) . "):" );
            array_map( $printSubtitleCallback, $acceptableSubtitles );

        }
        else
        {
            $output->writeln( "No acceptable subtitles" );
        }

        $output->writeln( "" );
        $output->writeln( "Unacceptable subtitles (" . count( $collection->getUnacceptableSubtitles() ) . "):" );
        array_map( $printSubtitleCallback, $collection->getUnacceptableSubtitles() );

//        $subtitle = $ballot->vote( $filename, $subtitles );
//        $output->writeln( "" );
//        $output->writeln( "Winner:" );
//        $printSubtitleCallback( $subtitle );
//
//        if ( $input->getOption( 'video-file' ) )
//        {
//            $output->writeln( "Saving best subtitle for " . $input->getOption( 'video-file' ) );
//            copy(
//                $subtitle->url,
//                $this->computeSubtitleFileName( $input->getOption( 'video-file' ), $subtitle->filename )
//            );
//        }
    }

    private function computeSubtitleFileName( $videoFile, $subtitleFile )
    {
        $videoExtension = pathinfo( $videoFile, PATHINFO_EXTENSION );
        $subtitleExtension = pathinfo( $subtitleFile, PATHINFO_EXTENSION );

        return preg_replace( "/\.$videoExtension$/", ".$subtitleExtension", $videoFile );
    }
}