<?php
/**
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace BD\Subber\SubtitledEpisodeRelease;

use BD\Subber\Episode\EpisodeMetadataFileParser;
use BD\Subber\Queue\TaskRepository;
use BD\Subber\Release\Parser\ReleaseParser;
use BD\Subber\Release\Release;
use BD\Subber\ReleaseSubtitles\IndexFactory;

class SubtitledEpisodeReleaseFactory
{
    /** @var \BD\Subber\ReleaseSubtitles\IndexFactory */
    private $subtitlesIndexFactory;

    /** @var \BD\Subber\Episode\EpisodeMetadataFileParser */
    private $episodeParser;

    /** @var \BD\Subber\Release\Parser\ReleaseParser */
    private $releaseParser;

    /** @var \BD\Subber\Queue\TaskRepository */
    private $taskRepository;

    public function __construct(
        IndexFactory $subtitlesIndexFactory,
        EpisodeMetadataFileParser $episodeParser,
        ReleaseParser $releaseParser,
        TaskRepository $taskRepository
    ) {

        $this->subtitlesIndexFactory = $subtitlesIndexFactory;
        $this->episodeParser = $episodeParser;
        $this->releaseParser = $releaseParser;
        $this->taskRepository = $taskRepository;
    }

    /**
     * @return SubtitledEpisodeRelease
     */
    public function buildFromReleaseName( $releaseName )
    {
        return $this->build(
            $releaseName,
            $this->taskRepository->loadByReleaseName( $releaseName )->getFile()
        );
    }

    public function buildFromLocalReleasePath( $localReleasePath )
    {
        return $this->build(
            $this->taskRepository->loadByLocalReleasePath( $localReleasePath )->getOriginalName(),
            $localReleasePath
        );
    }


    public function build( $releaseName, $localReleasePath )
    {
        $release = $this->buildObjectFromRelease( $this->releaseParser->parseReleaseName( $releaseName ) );

        try {
            $release->setEpisode( $this->episodeParser->parseFromEpisodeFilePath( $localReleasePath ) );
        } catch( \InvalidArgumentException $e ) {
            // doesn't matter, we return null. We might wanna log this, though.
        }
        $release->setSubtitlesIndex( $this->subtitlesIndexFactory->build( $releaseName ) );

        return $release;
    }

    /**
     * Builds the Subtitled Episode Release from the Release object's properties
     * @param Release $release
     * @return SubtitledEpisodeRelease
     */
    private function buildObjectFromRelease( Release $release )
    {
        $subtitledEpisodeRelease = new SubtitledEpisodeRelease();

        foreach ( $release as $propertyName => $propertyValue ) {
            $subtitledEpisodeRelease->$propertyName = $release->$propertyName;
        }
        return $subtitledEpisodeRelease;
    }
}
