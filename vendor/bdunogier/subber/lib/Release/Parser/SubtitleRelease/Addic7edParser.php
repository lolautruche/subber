<?php
namespace BD\Subber\Release\Parser\SubtitleRelease;

use BD\Subber\Release\Parser\ReleaseParser;
use BD\Subber\Release\Parser\ReleaseParserException;
use BD\Subber\Subtitles\Subtitle;

/**
 * Parses subtitles names from Addic7ed
 *
 * Vikings - 03x02 - The Wanderer.WEB-DL-BS.English.C.orig.Addic7ed.com.srt
 * Bitten - 02x04 - Dead Meat.KILLERS.English.C.orig.Addic7ed.com.srt
 * Allegiance - 01x04 - Chasing Ghosts.LOL.French.C.updated.Addic7ed.com
 */
class Addic7edParser implements ReleaseParser
{
    /**
     * @param string $releaseName
     * @return \BD\Subber\Subtitles\Subtitle
     */
    public function parseReleaseName( $releaseName )
    {
        $release = new Subtitle( ['name' => $releaseName] );
        $release->author = 'addic7ed';

        // addic7ed.com
        $releaseParts = explode( '.', strtolower( $releaseName ) );
        if ( array_pop( $releaseParts ) != 'com' || array_pop( $releaseParts ) != 'addic7ed' )
            throw new ReleaseParserException( $releaseName, "addic7ed.com string not found" );

        // orig or updated
        $status = array_pop( $releaseParts );
        if ( !in_array( $status, ['orig', 'updated'] ) ) {
            throw new ReleaseParserException( $releaseName, "$status isn't a valid status" );
        }

        // C thing in the release name
        $c = array_pop( $releaseParts );
        if ( $c != 'c' )
            throw new ReleaseParserException( $releaseName, "$c isn't the 'C' thing" );

        // can be hearing impaired, or language
        $next = array_pop( $releaseParts );
        if ( $next == 'hi' )
        {
            $release->isHearingImpaired = true;
            $next = array_pop( $releaseParts );
        }

        // language
        if ( in_array( $next, ['english', 'french'] ) ) {
            $release->language = $this->getLanguageCode( $next );
        }

        // group thing
        list( $release->source, $release->group ) = $this->resolveSourceThing( array_pop( $releaseParts ) );

        return $release;
    }

    private function resolveSourceThing( $sourceThingString )
    {
        $source = null;
        $group = null;

        if ( $sourceThingString === 'web-dl' )
        {
            $source = 'web-dl';
        }

        // killers-translate, web-dl-bs, ...
        if ( preg_match( '/^(.*?)\-([^\-]+)$/', $sourceThingString, $m ) )
        {
            if ( $m[1] == 'web-dl' ) {
                $source = $m[1];
                $group = $m[2];
            } else {
                $group = $m[1];
                if ( $m[2] != 'translate' ) {
                    $source = $m[2];
                }
            }

        }

        if ( $group === null )
        {
            $group = $sourceThingString;
        }

        return [$source, $group];
    }

    private function getLanguageCode( $languageString )
    {
        $map = ['english' => 'en', 'french' => 'fr'];
        return isset( $map[$languageString] ) ? $map[$languageString] : null;
    }
}