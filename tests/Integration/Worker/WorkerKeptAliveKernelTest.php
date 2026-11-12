<?php

declare(strict_types=1);

namespace Nowo\TagInputBundle\Tests\Integration\Worker;

use Nowo\TagInputBundle\Form\TagType;
use Nowo\TagInputBundle\Tests\Kernel\TestKernel;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormFactoryInterface;
use Twig\Environment;

use function gc_collect_cycles;
use function json_encode;
use function memory_get_usage;
use function sprintf;
use function str_contains;
use function strtoupper;

use const JSON_THROW_ON_ERROR;

/**
 * REQ-RUNTIME-002: one kept-alive kernel (FrankenPHP worker, kernel reset false), `services_resetter` never called.
 * A host form containing the tag widget is built, submitted and rendered N times alternating two inputs with
 * different options; each render must contain only its own data and memory must stay flat.
 */
final class WorkerKeptAliveKernelTest extends TestCase
{
    private const ITERATIONS = 1200;
    private const WARM_UP    = 100;
    private const MARGIN     = 4 * 1024 * 1024;

    public function testAlternatingFormsInOneKernelLeakNothingAndKeepMemoryFlat(): void
    {
        // Production-like kernel (debug false), as the worker runs it.
        $kernel = new TestKernel('test', false);
        $kernel->boot();
        $container = $kernel->getContainer();
        $factory   = $container->get('test.form.factory');
        $twig      = $container->get('test.twig');
        self::assertInstanceOf(FormFactoryInterface::class, $factory);
        self::assertInstanceOf(Environment::class, $twig);

        $inputs = [
            'alpha' => ['tags' => ['alpha-one', 'alpha-two'], 'options' => ['max_tags' => 3, 'whitelist' => ['alpha-one', 'alpha-two'], 'placeholder' => 'ALPHA-PH'], 'other' => 'beta'],
            'beta'  => ['tags' => ['beta-one'], 'options' => ['max_tags' => 7, 'pattern' => '^beta-', 'placeholder' => 'BETA-PH'], 'other' => 'alpha'],
        ];

        $baseline = 0;
        for ($i = 0; $i < self::ITERATIONS; ++$i) {
            if ($i === self::WARM_UP) {
                gc_collect_cycles();
                $baseline = memory_get_usage();
            }

            $who  = $i % 2 === 0 ? 'alpha' : 'beta';
            $data = $inputs[$who];
            $form = $factory->createNamed('host', FormType::class)
                ->add('tags', TagType::class, $data['options'])
                ->add('title', TextType::class);
            $form->submit(['tags' => json_encode($data['tags'], JSON_THROW_ON_ERROR), 'title' => 'title-' . $who]);

            self::assertTrue($form->isSynchronized(), sprintf('iteration %d', $i));
            self::assertSame($data['tags'], $form->get('tags')->getData(), sprintf('iteration %d', $i));

            $html = $twig->createTemplate('{{ form_widget(form) }}')->render(['form' => $form->createView()]);
            self::assertStringContainsString(strtoupper($who) . '-PH', $html, sprintf('iteration %d', $i));
            self::assertStringContainsString('title-' . $who, $html);
            self::assertFalse(str_contains($html, $data['other'] . '-one'), sprintf('iteration %d leaked the other input', $i));
            self::assertFalse(str_contains($html, strtoupper($data['other']) . '-PH'), sprintf('iteration %d leaked the other placeholder', $i));
            self::assertFalse(str_contains($html, 'title-' . $data['other']), sprintf('iteration %d leaked the other title', $i));
        }

        gc_collect_cycles();
        $growth = memory_get_usage() - $baseline;
        self::assertLessThan(self::MARGIN, $growth, sprintf('memory grew by %d bytes over %d iterations', $growth, self::ITERATIONS - self::WARM_UP));

        $kernel->shutdown();
    }
}
