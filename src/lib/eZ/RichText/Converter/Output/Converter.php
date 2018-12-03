<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace EzSystems\EzPlatformRichText\eZ\RichText\Converter\Output;

use DOMDocument;
use EzSystems\EzPlatformRichText\SPI\ConverterDispatcher;
use EzSystems\EzPlatformRichText\SPI\OutputConverter;

class Converter implements OutputConverter
{
    /**
     * @var \EzSystems\EzPlatformRichText\SPI\ConverterDispatcher
     */
    private $converterDispatcher;

    public function __construct(ConverterDispatcher $converterDispatcher)
    {
        $this->converterDispatcher = $converterDispatcher;
    }

    /**
     * Convert RichText DocBook into HTML5 output.
     *
     * @param \DOMDocument $xmlDoc
     *
     * @return \DOMDocument
     */
    public function convert(DOMDocument $xmlDoc): DOMDocument
    {
        return $this->converterDispatcher->dispatch($xmlDoc);
    }
}
