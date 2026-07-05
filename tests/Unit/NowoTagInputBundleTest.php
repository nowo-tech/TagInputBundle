<?php

declare(strict_types=1);

namespace Nowo\TagInputBundle\Tests\Unit;

use Nowo\TagInputBundle\DependencyInjection\Compiler\TwigPathsPass;
use Nowo\TagInputBundle\NowoTagInputBundle;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @covers \Nowo\TagInputBundle\NowoTagInputBundle
 */
final class NowoTagInputBundleTest extends TestCase
{
    public function testBundleRegistersTwigCompilerPass(): void
    {
        $bundle    = new NowoTagInputBundle();
        $container = new ContainerBuilder();
        $bundle->build($container);

        $passes = $container->getCompilerPassConfig()->getPasses();
        $found  = false;
        foreach ($passes as $pass) {
            if ($pass instanceof TwigPathsPass) {
                $found = true;
                break;
            }
        }

        self::assertTrue($found);
    }
}
