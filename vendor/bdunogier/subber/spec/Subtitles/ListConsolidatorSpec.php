<?php

namespace spec\BD\Subber\Subtitles;

use BD\Subber\Subtitles\Subtitle;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ListConsolidatorSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType( 'BD\Subber\Subtitles\ListConsolidator' );
    }

    function it_forks_subtitles_with_multiple_groups()
    {
        $subtitles = [
            new Subtitle( ['group' => ['lol', 'dimension']] )
        ];
        $result = $this->consolidate( $subtitles );
        $result->shouldBeAnArrayOfSubtitles( 'lol' );
        $result->shouldHaveOneSubtitleWithGroup( 'lol' );
        $result->shouldHaveOneSubtitleWithGroup( 'dimension' );
    }

    function it_forks_subtitles_with_multiple_resolutions()
    {
        $result = $this->consolidate( [ new Subtitle( ['resolution' => ['720p', '1080p']] ) ] );
        $result->shouldBeAnArrayOfSubtitles();
        $result->shouldHaveOneSubtitleWithResolution( '720p' );
        $result->shouldHaveOneSubtitleWithResolution( '1080p' );
    }

    function it_forks_subtitles_with_inconsistent_group_and_resolution()
    {
        $result = $this->consolidate( [ new Subtitle( ['resolution' => '720p', 'group' => 'lol'] ) ] );
        $result->shouldBeAnArrayOfSubtitles();
        $result->shouldHaveOneSubtitleWithGroupAndResolution( 'lol', '480p' );
        $result->shouldHaveOneSubtitleWithResolution( '720p' );
    }

    function getMatchers()
    {
        return [
            'beAnArrayOfSubtitles' => function( $subject ) {
                foreach ($subject as $object) {
                    if ( !$object instanceof Subtitle ) return false;
                }
                return true;
            },
            'haveOneSubtitleWithGroup' => function( $subject, $expectedGroup ) {
                foreach ( $subject as $subtitle ) {
                    if ( $subtitle->group == $expectedGroup ) return true;
                }
                return false;
            },
            'haveOneSubtitleWithResolution' => function( $subject, $expectedResolution ) {
                foreach ( $subject as $subtitle ) {
                    if ( $subtitle->resolution == $expectedResolution ) return true;
                }
                return false;
            },
            'haveOneSubtitleWithGroupAndResolution' => function( $subject, $expectedGroup, $expectedResolution ) {
                foreach ( $subject as $subtitle ) {
                    if ( $subtitle->resolution == $expectedResolution && $subtitle->group == $expectedGroup ) return true;
                }
                return false;
            }
        ];
    }
}