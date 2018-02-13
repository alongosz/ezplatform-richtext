<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace EzSystems\Tests\EzPlatformRichTextFieldType\UI\Translation;

use EzSystems\EzPlatformRichTextFieldType\UI\Translation\CustomTagsTranslationExtractor;
use PHPUnit\Framework\TestCase;

/**
 * Test custom JMS Translation Extractor for Custom Tags.
 */
class CustomTagsTranslationExtractorTest extends TestCase
{
    const TRANSLATION_DOMAIN = 'custom_tags';

    /**
     * @param array $customTagsConfiguration
     * @param array $expectedTranslations
     *
     * @dataProvider providerForTestExtract
     */
    public function testExtract(array $customTagsConfiguration, array $expectedTranslations)
    {
        $extractor = new CustomTagsTranslationExtractor(
            $customTagsConfiguration,
            static::TRANSLATION_DOMAIN
        );

        $catalogue = $extractor->extract();

        foreach ($expectedTranslations as $label => $expectedLocaleString) {
            $message = $catalogue->get($label, static::TRANSLATION_DOMAIN);
            self::assertEquals($expectedLocaleString, $message->getLocaleString());
        }
    }

    /**
     * Data Provider for testExtract.
     *
     * @see testExtract
     *
     * @return array
     */
    public function providerForTestExtract()
    {
        // Note: configuration contains only keys/values which are necessary for Extractor to work
        return [
            0 => [
                [
                    'ezvideo' => [
                        'attributes' => [
                            'title' => [],
                            'width' => [],
                        ],
                    ],
                ],
                [
                    'ezrichtext.custom_tags.ezvideo.label' => 'Video',
                    'ezrichtext.custom_tags.ezvideo.description' => '',
                    'ezrichtext.custom_tags.ezvideo.attributes.title.label' => 'Title',
                    'ezrichtext.custom_tags.ezvideo.attributes.width.label' => 'Width',
                ],
            ],
            1 => [
                [
                    'equation' => [
                        'attributes' => [
                            'processor' => [],
                        ],
                    ],
                ],
                [
                    'ezrichtext.custom_tags.equation.label' => 'Equation',
                    'ezrichtext.custom_tags.equation.description' => '',
                    'ezrichtext.custom_tags.equation.attributes.processor.label' => 'Processor',
                ],
            ],
        ];
    }
}
