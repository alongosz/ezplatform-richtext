<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace EzSystems\Tests\EzPlatformRichTextBundle\Templating\Twig\Extension;

use EzSystems\EzPlatformRichTextBundle\Templating\Twig\Extension\RichTextExtension;
use EzSystems\Tests\EzPlatformRichTextBundle\BaseBundleTestCase;

class RichTextExtensionTest extends BaseBundleTestCase
{
    /**
     * @var \EzSystems\EzPlatformRichTextBundle\Templating\Twig\Extension\RichTextExtension
     */
    private $extension;

    protected function setUp()
    {
        parent::setUp();

        $this->extension = $this->getSetupFactory()->getServiceContainer()->get(RichTextExtension::class);
    }

    public function testRichTextToHtml5()
    {
        self::assertEquals(RichTextExtension::class, $this->extension);
    }

    public function testRichTextToHtml5Edit()
    {
        self::markTestIncomplete(__METHOD__ . ' has not been implemented yet');
    }
}
