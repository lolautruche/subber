<?php
namespace BD\Subber\ReleaseSubtitles\Matcher;

use BD\Subber\Release\Release;
use BD\Subber\ReleaseSubtitles\Matcher;
use BD\Subber\Subtitles\Subtitle;

/**
 * Matches subtitles to releases by comparing the source
 */
class SourceMatcher implements Matcher
{
    public function matches( Subtitle $subtitle, Release $release )
    {
        // we should test the group, but the source is usually sufficient, and groups are compatible over hdtv
        if ($subtitle->source != $release->source) {
            return false;
        }

        return true;
    }
}