<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace EzSystems\Tests\EzPlatformRichTextBundle\SetupFactory\Service;

use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Dummy authorization checker for the purpose of the Bundle tests.
 */
class NullAuthorizationChecker implements AuthorizationCheckerInterface
{
    /**
     * Checks if the attributes are granted against the current authentication token and optionally supplied subject.
     *
     * @param mixed $attributes
     * @param mixed $subject
     *
     * @return bool
     */
    public function isGranted($attributes, $subject = null)
    {
        return true;
    }
}
