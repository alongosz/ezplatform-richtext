<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace EzSystems\EzPlatformRichText\eZ\RichText\Converter\Output;

use DOMDocument;
use DOMNode;
use DOMXPath;
use EzSystems\EzPlatformRichText\SPI\ConverterDispatcher;

/**
 * RichText DocBook output converter dispatcher.
 */
class Dispatcher implements ConverterDispatcher
{
    /**
     * @var \EzSystems\EzPlatformRichText\eZ\RichText\Converter[]
     */
    private $converters;

    /**
     * @param iterable $converters
     */
    public function __construct(iterable $converters)
    {
        $this->converters = $converters;
    }

    /**
     * Dispatch DOMDocument to Converters.
     *
     * @param \DOMDocument $document
     *
     * @return \DOMDocument
     */
    public function dispatch(DOMDocument $document): DOMDocument
    {
        $xpath = new DOMXPath($document);
        $xpath->registerNamespace('docbook', 'http://docbook.org/ns/docbook');
        $xpathExpression = '//docbook:ezcontent';
        $nestedDocumentsNodes = $xpath->query($xpathExpression);

        // sort inner Documents to start processing from the most nested one
        $sortedDocumentsNodes = [];
        foreach ($nestedDocumentsNodes as $nestedDocumentNode) {
            /** @var \DOMElement $nestedDocumentNode */
            $depth = $this->getNodeDepth($nestedDocumentNode);
            $sortedDocumentsNodes[$depth][] = $nestedDocumentNode;
        }

        krsort($sortedDocumentsNodes, SORT_NUMERIC);

        // flatten after ordering
        $sortedDocumentsNodes = array_merge(...$sortedDocumentsNodes);

        foreach ($sortedDocumentsNodes as $documentNode) {
            /** @var \DOMElement $documentNode */
            $innerDocument = new DOMDocument();
            $innerNode = $innerDocument->appendChild(
                $innerDocument->importNode($documentNode, true)
            );
            foreach ($this->converters as $converter) {
                $innerDocument = $converter->convert($innerDocument);
            }

            $fragment = $innerDocument->createDocumentFragment();
            $fragment->appendXML($innerDocument->saveHTML());
            $documentNode->parentNode->replaceChild($fragment, $documentNode);
        }

        return $document;
    }

    /**
     * Returns depth of given $node in a DOMDocument.
     *
     * @param \DOMNode $node
     *
     * @return int
     */
    protected function getNodeDepth(DOMNode $node)
    {
        // initial depth for top level elements (to avoid "ifs")
        $depth = -2;

        while ($node) {
            ++$depth;
            $node = $node->parentNode;
        }

        return $depth;
    }
}
