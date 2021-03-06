<?php

/**
 * This file is part of elythy/prooph-fixtures-bundle.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace DummyBundle\DataFixtures;

use Prooph\Fixtures\Fixture\Fixture;

class AFixture implements Fixture
{
    /**
     * {@inheritdoc}
     */
    public function load(): void
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'AFixture';
    }
}
