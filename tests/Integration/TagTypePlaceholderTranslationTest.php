<?php

declare(strict_types=1);

namespace Nowo\TagInputBundle\Tests\Integration;

use Nowo\TagInputBundle\Form\TagType;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Symfony\Bridge\Twig\Extension\FormExtension;
use Symfony\Bridge\Twig\Extension\TranslationExtension;
use Symfony\Bridge\Twig\Form\TwigRendererEngine;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormRenderer;
use Symfony\Component\Form\Forms;
use Symfony\Component\Translation\DataCollectorTranslator;
use Symfony\Component\Translation\Loader\YamlFileLoader;
use Symfony\Component\Translation\Translator;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\RuntimeLoader\FactoryRuntimeLoader;

use function array_filter;
use function array_map;
use function array_values;
use function basename;
use function dirname;
use function explode;
use function glob;
use function preg_match;

/**
 * The bundled placeholder is translated once: TagType translates it for the frontend data attribute and hands the
 * untranslated key to the `placeholder` attribute, which the form theme translates in the field domain. Rendering the
 * widget looks up no missing message (the profiler logged the translated text as missing in the bundle domain).
 */
final class TagTypePlaceholderTranslationTest extends TestCase
{
    /**
     * @return iterable<string, array{string, string}>
     */
    public static function locales(): iterable
    {
        yield 'en' => ['en', 'Add tags and press Enter'];
        yield 'es' => ['es', 'Añade etiquetas y pulsa Enter'];
    }

    #[DataProvider('locales')]
    public function testWidgetPlaceholderIsTranslatedOnce(string $locale, string $expected): void
    {
        $translator = new Translator($locale);
        $translator->addLoader('yaml', new YamlFileLoader());
        foreach (glob(dirname(__DIR__, 2) . '/src/Resources/translations/*.yaml') ?: [] as $file) {
            [$domain, $fileLocale] = explode('.', basename($file));
            $translator->addResource('yaml', $file, $fileLocale, $domain);
        }
        $collector = new DataCollectorTranslator($translator);

        $loader = new FilesystemLoader([dirname((string) (new ReflectionClass(FormExtension::class))->getFileName(), 2) . '/Resources/views/Form']);
        $loader->addPath(dirname(__DIR__, 2) . '/src/Resources/views', 'NowoTagInputBundle');
        $twig   = new Environment($loader, ['strict_variables' => true]);
        $engine = new TwigRendererEngine(['@NowoTagInputBundle/Form/tag_input_theme.html.twig'], $twig);
        $twig->addRuntimeLoader(new FactoryRuntimeLoader([FormRenderer::class => static fn (): FormRenderer => new FormRenderer($engine)]));
        $twig->addExtension(new FormExtension($collector));
        $twig->addExtension(new TranslationExtension($collector));

        $factory = Forms::createFormFactoryBuilder()
            ->addType(new TagType(defaultPlaceholder: TagType::DEFAULT_PLACEHOLDER_KEY, translator: $collector))
            ->getFormFactory();
        $form = $factory->createBuilder(FormType::class)->add('tags', TagType::class)->getForm()->createView();

        $html = $twig->createTemplate('{{ form_widget(form.tags) }}')->render(['form' => $form]);

        self::assertSame(1, preg_match('/ placeholder="([^"]*)"/', $html, $placeholder));
        self::assertSame($expected, $placeholder[1]);
        self::assertStringContainsString('data-nowo-tag-input-placeholder-value="' . $expected . '"', $html);

        $missing = array_filter(
            $collector->getCollectedMessages(),
            static fn (array $message): bool => $message['state'] === DataCollectorTranslator::MESSAGE_MISSING,
        );
        self::assertSame([], array_values(array_map(static fn (array $message): string => $message['locale'] . ' ' . $message['domain'] . ': ' . $message['id'], $missing)));
    }
}
