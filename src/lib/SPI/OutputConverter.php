<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace EzSystems\EzPlatformRichText\SPI;

use EzSystems\EzPlatformRichText\eZ\RichText\Converter;

/**
 * RichText Output Converter marker interface.
 * Provides common entry point for DocBook XML to be converted into XHTML5 output.
 *
 * @see \EzSystems\EzPlatformRichText\eZ\RichText\Converter\Output\Converter
 * @see \EzSystems\EzPlatformRichText\eZ\RichText\Converter\Output\Dispatcher
 */
interface OutputConverter extends Converter
{
}
