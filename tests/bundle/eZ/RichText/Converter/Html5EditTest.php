<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace EzSystems\Tests\EzPlatformRichTextBundle\eZ\RichText\Converter;

use EzSystems\EzPlatformRichTextBundle\eZ\RichText\Converter;
use PHPUnit\Framework\TestCase;

class Html5EditTest extends TestCase
{
    /**
     * @var \eZ\Bundle\EzPublishCoreBundle\FieldType\RichText\Converter\Html5Edit
     */
    private $converter;

    public function setUp()
    {
        $this->markTestIncomplete('Refactoring needed');

        $packageResourcesDir = __DIR__ . '/../../../../../src/lib/eZ/RichText/Resources';
        $this->converter = new Converter\Html5Edit(
            "{$packageResourcesDir}/stylesheets/docbook/xhtml5/edit/xhtml5.xsl",
            $this->getConfigResolverMock(
                [
                    'fieldtypes.ezrichtext.edit_custom_xsl' => [
                        [
                            'path' => "{$packageResourcesDir}/stylesheets/docbook/xhtml5/edit/core.xsl",
                            'priority' => 0,
                        ],
                    ],
                ]
            )
        );
    }

    /**
     * @dataProvider providerForTestConvert
     */
    public function testConvert(string $inputFixture, string $resultFixture)
    {
        $inputDocument = new \DOMDocument();
        $inputDocument->load($inputFixture);

        $convertedDocument = new \DOMDocument();
        $convertedDocument->load($resultFixture);

        self::assertEquals(
            $convertedDocument,
            $this->converter->convert($inputDocument)
        );
    }

    /**
     * Data provider for testConvert.
     *
     * @see testConvert
     *
     * @return array
     */
    public function providerForTestConvert()
    {
        $fixture = __DIR__ . '/../../../../lib/eZ/RichText/Converter/Xslt/_fixtures/docbook/034-nestedTemplate.xml';
        $output = __DIR__ . '/../../../../lib/eZ/RichText/Converter/Xslt/_fixtures/xhtml5/edit/034-nestedTemplate.xml';

        return [
            [$fixture, $output],
        ];
    }
}
