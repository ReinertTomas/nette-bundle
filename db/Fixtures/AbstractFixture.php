<?php
declare(strict_types=1);

namespace Database\Fixtures;

use Doctrine\Common\DataFixtures\AbstractFixture as DoctrineAbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Nette\DI\Container;
use Nettrine\Fixtures\ContainerAwareInterface;

abstract class AbstractFixture extends DoctrineAbstractFixture implements ContainerAwareInterface, OrderedFixtureInterface
{

    protected Container $container;

    public function setContainer(Container $container): void
    {
        $this->container = $container;
    }

    public function getOrder(): int
    {
        return 0;
    }

}