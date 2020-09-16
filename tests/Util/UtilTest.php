<?php

declare(strict_types=1);

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Util;

use App\Util\Util;
use Packagist\Api\Result\Package;
use PHPUnit\Framework\TestCase;

/**
 * @author Oskar Stark <oskarstark@googlemail.com>
 */
final class UtilTest extends TestCase
{
    /**
     * @dataProvider getRepositoryNameWithoutVendorPrefixProvider
     */
    public function getRepositoryNameWithoutVendorPrefix(string $expected, string $repository): void
    {
        $package = new Package();
        $package->fromArray([
            'repostory' => $repository,
        ]);

        self::assertSame(
            $expected,
            Util::getRepositoryNameWithoutVendorPrefix($package)
        );
    }

    public function getRepositoryNameWithoutVendorPrefixThrowsExceptionIfNameDoesNotContainSlash(string $expected, string $repository): void
    {
        $package = new Package();
        $package->fromArray([
            'repostory' => $repository = 'sonata-projectSonataAdminBundle',
        ]);

        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage(sprintf(
            'Could not get repository name without vendor prefix for: %s',
            $repository
        ));

        Util::getRepositoryNameWithoutVendorPrefix($package);
    }

    public function getRepositoryNameWithoutVendorPrefixThrowsExceptionIfNameEndsWithSlash(string $expected, string $repository): void
    {
        $package = new Package();
        $package->fromArray([
            'repostory' => $repository = 'sonata-projectSonataAdminBundle/',
        ]);

        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage(sprintf(
            'Could not get repository name without vendor prefix for: %s',
            $repository
        ));

        Util::getRepositoryNameWithoutVendorPrefix($package);
    }

    /**
     * @return iterable<array{0: string, 1: string}>
     */
    public function getRepositoryNameWithoutVendorPrefixProvider(): iterable
    {
        yield [
            'SonataAdminBundle',
            'sonata-project/SonataAdminBundle',
        ];

        yield [
            'SonataAdminBundle',
            'sonata-project/SonataAdminBundle.git',
        ];
    }
}