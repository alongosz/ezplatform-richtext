<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace EzSystems\EzPlatformRichText\eZ\RichText\Converter\Render;

use DOMDocument;
use DOMElement;
use DOMNode;
use DOMXPath;
use EzSystems\EzPlatformRichText\eZ\RichText\Converter;
use EzSystems\EzPlatformRichText\eZ\RichText\Converter\Render;
use EzSystems\EzPlatformRichText\eZ\RichText\RendererInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * RichText Template converter injects rendered template payloads into template elements.
 */
class Template extends Render implements Converter
{
    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * RichText Template converter constructor.
     *
     * @param \EzSystems\EzPlatformRichText\eZ\RichText\RendererInterface $renderer
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        RendererInterface $renderer,
        LoggerInterface $logger = null
    ) {
        $this->logger = $logger ?? new NullLogger();

        parent::__construct($renderer);
    }

    /**
     * Injects rendered payloads into template elements.
     *
     * @param \DOMDocument $document
     *
     * @return \DOMDocument
     */
    public function convert(DOMDocument $document)
    {
        $xpath = new DOMXPath($document);
        $xpath->registerNamespace('docbook', 'http://docbook.org/ns/docbook');
        $xpathExpression = '//docbook:eztemplate | //docbook:eztemplateinline';
        $templates = $xpath->query($xpathExpression);
        /** @var \DOMElement[] $templatesSorted */
        $templatesSorted = [];

        foreach ($templates as $template) {
            /** @var \DOMElement $template */
            $depth = $this->getNodeDepth($template);
            $templatesSorted[$depth][] = $template;
        }

        krsort($templatesSorted, SORT_NUMERIC);

        foreach ($templatesSorted as $templates) {
            foreach ($templates as $template) {
                $this->processTemplate($document, $template);
            }
        }

        return $document;
    }

    /**
     * Processes given template $template in a given $document.
     *
     * @param \DOMDocument $document
     * @param \DOMElement $template
     */
    protected function processTemplate(DOMDocument $document, DOMElement $template)
    {
        $content = null;
        $templateName = $template->getAttribute('name');
        $templateType = $template->hasAttribute('type') ? $template->getAttribute('type') : 'tag';
        $parameters = [
            'name' => $templateName,
            'params' => $this->extractConfiguration($template),
            'content' => '',
        ];

        if ($template->getElementsByTagName('ezcontent')->length > 0) {
            $contentNode = $template->getElementsByTagName('ezcontent')->item(0);
            $parameters['content'] = $this->getCustomTemplateContent($contentNode);
        }

        if ($template->hasAttribute('ezxhtml:align')) {
            $parameters['align'] = $template->getAttribute('ezxhtml:align');
        }
        $content = $this->renderer->renderTemplate(
            $templateName,
            $templateType,
            $parameters,
            $template->localName === 'eztemplateinline'
        );

        if (isset($content)) {
            // If current tag is wrapped inside another template tag we can't use CDATA section
            // for its content as these can't be nested.
            // CDATA section will be used only for content of root wrapping tag, content of tags
            // inside it will be added as XML fragments.
            if ($this->isWrapped($template)) {
                $fragment = $document->createDocumentFragment();
                $fragment->appendXML($content);
                $template->parentNode->replaceChild($fragment, $template);
            } else {
                $payload = $document->createElement('ezpayload');
                $payload->appendChild($document->createCDATASection($content));
                $template->appendChild($payload);
            }
        }
    }

    /**
     * Returns if the given $node is wrapped inside another template node.
     *
     * @param \DOMNode $node
     *
     * @return bool
     */
    protected function isWrapped(DomNode $node)
    {
        while ($node = $node->parentNode) {
            if ($node->localName === 'eztemplate' || $node->localName === 'eztemplateinline') {
                return true;
            }
        }

        return false;
    }

    /**
     * Returns depth of given $node in a DOMDocument.
     *
     * @param \DOMNode $node
     *
     * @return int
     */
    protected function getNodeDepth(DomNode $node)
    {
        // initial depth for top level elements (to avoid "ifs")
        $depth = -2;

        while ($node) {
            ++$depth;
            $node = $node->parentNode;
        }

        return $depth;
    }

    /**
     * Returns XML fragment string for given $node.
     *
     * @param \DOMNode $node
     *
     * @return string
     */
    protected function getCustomTemplateContent(DOMNode $node)
    {
        $xmlString = '';

        /** @var \DOMNode $child */
        foreach ($node->childNodes as $child) {
            $xmlString .= $node->ownerDocument->saveXML($child);
        }

        return $xmlString;
    }

    /**
     * Extracts configuration hash from embedded template.
     *
     * @param \DOMElement $template
     *
     * @return array
     */
    protected function extractConfiguration(DOMElement $template)
    {
        $hash = [];

        $xpath = new DOMXPath($template->ownerDocument);
        $xpath->registerNamespace('docbook', 'http://docbook.org/ns/docbook');
        $configValuesNodes = $xpath->query('./docbook:ezconfig', $template);

        if ($configValuesNodes->length) {
            $hash = $this->extractHash($configValuesNodes->item(0));
        }

        return $hash;
    }
}
