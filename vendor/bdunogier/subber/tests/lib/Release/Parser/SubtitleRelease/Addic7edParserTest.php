<?php
namespace tests\BD\Subber\Release\Parser\SubtitleRelease\Addic7edParser;

use BD\Subber\Release\Parser\SubtitleRelease\Addic7edParser;

class Addic7edParserTest extends \PHPUnit_Framework_TestCase
{
    /** @var \BD\Subber\Release\Parser\SubtitleRelease\Addic7edParser */
    private $parser;

    /**
     * @dataProvider getValidReleases
     */
    public function testParseValidRelease( $releaseName, $expectedProperties )
    {
        $parser = new Addic7edParser();
        $release = $parser->parseReleaseName( $releaseName );

        foreach ( $expectedProperties as $property => $value )
        {
            self::assertAttributeEquals( $value, $property, $release );
        }
    }

    public function getValidReleases()
    {
        return [
            [
                'Bitten - 02x04 - Dead Meat.KILLERS.English.C.orig.Addic7ed.com',
                [
                    'name' => 'Bitten - 02x04 - Dead Meat.KILLERS.English.C.orig.Addic7ed.com',
                    'group' => 'killers',
                    'author' => 'addic7ed',
                    'language' => 'en'
                ]
            ],
            [
                'Vikings - 03x02 - The Wanderer.WEB-DL-BS.English.C.orig.Addic7ed.com',
                [
                    'name' => 'Vikings - 03x02 - The Wanderer.WEB-DL-BS.English.C.orig.Addic7ed.com',
                    'group' => 'bs',
                    'author' => 'addic7ed',
                    'language' => 'en',
                    'source' => 'web-dl'
                ]
            ],
            [
                'Vikings - 03x01 - Mercenary.KILLERS-TRANSLATE.French.C.updated.Addic7ed.com',
                [
                    'name' => 'Vikings - 03x01 - Mercenary.KILLERS-TRANSLATE.French.C.updated.Addic7ed.com',
                    'group' => 'killers',
                    'author' => 'addic7ed',
                    'language' => 'fr'
                ]
            ],
            [
                'Vikings - 03x01 - Mercenary.KILLERS.English.HI.C.orig.Addic7ed.com',
                [
                    'name' => 'Vikings - 03x01 - Mercenary.KILLERS.English.HI.C.orig.Addic7ed.com',
                    'group' => 'killers',
                    'author' => 'addic7ed',
                    'language' => 'en',
                    'isHearingImpaired' => true
                ]
            ],
            [
                'Gotham - 01x17 - Red Hood.LOL.French.C.updated.Addic7ed.com',
                [
                    'name' => 'Gotham - 01x17 - Red Hood.LOL.French.C.updated.Addic7ed.com',
                    'group' => 'lol',
                    'author' => 'addic7ed',
                    'language' => 'fr'
                ]
            ]
        ];
    }
}