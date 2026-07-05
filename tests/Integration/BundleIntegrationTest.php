<?php

declare(strict_types=1);

namespace Nowo\TagInputBundle\Tests\Integration;

use Nowo\TagInputBundle\Form\TagType;
use Nowo\TagInputBundle\Tests\Kernel\TestKernel;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Integration tests: kernel boots with the bundle and core services are wired.
 */
#[RunTestsInSeparateProcesses]
final class BundleIntegrationTest extends KernelTestCase
{
    protected static function getKernelClass(): string
    {
        return TestKernel::class;
    }

    public function testKernelBoots(): void
    {
        self::bootKernel();
        self::assertTrue(self::getContainer()->has('kernel'));
    }

    public function testTagTypeIsRegistered(): void
    {
        self::bootKernel();
        $container = self::getContainer();

        self::assertTrue($container->has(TagType::class));
        self::assertInstanceOf(TagType::class, $container->get(TagType::class));
    }

    public function testBundleConfigurationParametersAreLoaded(): void
    {
        self::bootKernel();
        $container = self::getContainer();

        self::assertSame('array', $container->getParameter('nowo_tag_input.value_format'));
        self::assertTrue($container->getParameter('nowo_tag_input.trim'));
        self::assertSame('form_div_layout.html.twig', $container->getParameter('nowo_tag_input.form_theme'));
    }
}
