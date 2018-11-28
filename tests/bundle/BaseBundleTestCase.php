<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace EzSystems\Tests\EzPlatformRichTextBundle;

use eZ\Publish\API\Repository\Tests\BaseTest;
use eZ\Publish\Core\Base\ServiceContainer;
use EzSystems\Tests\EzPlatformRichTextBundle\SetupFactory\BundleSetupFactory;

class BaseBundleTestCase extends BaseTest
{
    protected function setUp()
    {
        putenv('setupFactory=' . BundleSetupFactory::class);

        parent::setUp();
    }

    /**
     * @return \eZ\Publish\Core\Base\ServiceContainer
     *
     * @throws \ErrorException
     */
    protected function getServiceContainer(): ServiceContainer
    {
        return $this->getSetupFactory()->getServiceContainer();
    }
}
