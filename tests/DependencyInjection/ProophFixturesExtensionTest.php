<?php

/**
 * This file is part of elythy/prooph-fixtures-bundle.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Prooph\Bundle\Fixtures\Tests\DependencyInjection;

use DummyBundle\DataFixtures\AFixture;
use DummyBundle\DataFixtures\AnotherFixture;
use PHPUnit\Framework\TestCase;
use Prooph\Bundle\Fixtures\Tests\ProophFixturesTestingKernel;
use Prooph\Fixtures\Provider\FixturesProvider;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ProophFixturesExtensionTest extends TestCase
{
    const FIXTURES_TAG = 'prooph_fixtures.fixtures';

    /**
     * @test
     */
    public function it_overrides_the_batches_size_parameter()
    {
        $batchesSize = 63217;

        $container = $this->loadContainerFromServicesRegistration(
            static function (ContainerBuilder $container) use ($batchesSize): void {
                $container->setParameter('prooph_fixtures.batches_size', $batchesSize);
            }
        );

        $this->assertEquals($batchesSize, $container->getParameter('prooph_fixtures.batches_size'));
    }

    /**
     * @test
     */
    public function it_provides_tagged_fixtures_to_the_provider()
    {
        $container = $this->loadContainerFromServicesRegistration(
            static function (ContainerBuilder $container): void {
                $container->autowire(AFixture::class)
                    ->addTag(self::FIXTURES_TAG);

                $container->autowire(AnotherFixture::class)
                    ->addTag(self::FIXTURES_TAG);
            }
        );

        $fixturesProvider = $this->getFixturesProvider($container);
        $fixtures = $fixturesProvider->all();

        $this->assertCount(2, $fixtures);
        $this->assertSame([AFixture::class, AnotherFixture::class], \array_keys($fixtures));
    }

    /**
     * @test
     */
    public function it_adds_the_tag_to_the_fixures()
    {
        $container = $this->loadContainerFromServicesRegistration(
            static function (ContainerBuilder $container): void {
                $container->autowire(AFixture::class)
                    ->setAutoconfigured(true);
            }
        );

        $fixturesProvider = $this->getFixturesProvider($container);
        $fixtures = $fixturesProvider->all();

        $this->assertContains(AFixture::class, \array_keys($fixtures));
    }

    private function getFixturesProvider(ContainerInterface $container): FixturesProvider
    {
        $fixturesProvider = $container->get(ProophFixturesTestingKernel::FIXTURES_PROVIDER_ID);

        return $fixturesProvider;
    }

    private function loadContainerFromServicesRegistration($registerServices): ContainerInterface
    {
        $kernel = new ProophFixturesTestingKernel('test', true);
        $kernel->registerServices($registerServices);
        $kernel->boot();

        return $kernel->getContainer();
    }
}
