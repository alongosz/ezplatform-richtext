<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace EzSystems\EzPlatformRichText\SPI;

use DOMDocument;

/**
 * DOMDocument Converter Dispatcher.
 */
interface ConverterDispatcher
{
    /**
     * Dispatch DOMDocument to Converters.
     *
     * @param \DOMDocument $document
     *
     * @return \DOMDocument
     */
    public function dispatch(DOMDocument $document): DOMDocument;
}
