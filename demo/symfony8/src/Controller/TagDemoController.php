<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\TagDemoType;
use Nowo\TagInputBundle\Form\ValueFormat;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use function sprintf;

final class TagDemoController extends AbstractController
{
    /**
     * @var array<string, array{title: string, help: string, initial: array<int, string>|string, options: array<string, mixed>}>
     */
    private const EXAMPLES = [
        'basic' => [
            'title'   => 'Basic tags (array value)',
            'help'    => 'Free-form tags stored as a PHP array of strings.',
            'initial' => ['php', 'symfony'],
            'options' => [
                'label' => 'Tags',
            ],
        ],
        'whitelist' => [
            'title'   => 'Whitelist with dropdown',
            'help'    => 'Only suggested technologies are accepted; dropdown helps discovery.',
            'initial' => ['php'],
            'options' => [
                'label'            => 'Technologies',
                'whitelist'        => ['php', 'symfony', 'twig', 'doctrine', 'vite'],
                'dropdown_enabled' => true,
            ],
        ],
        'comma-string' => [
            'title'   => 'Comma-separated string output',
            'help'    => 'Same UI, but the submitted model value is a comma-separated string.',
            'initial' => 'alpha,beta',
            'options' => [
                'label'        => 'Keywords',
                'value_format' => ValueFormat::STRING,
                'max_tags'     => 5,
            ],
        ],
    ];

    #[Route(path: '/', name: 'app_root', methods: ['GET'])]
    public function root(): RedirectResponse
    {
        return $this->redirectToRoute('app_demo_index');
    }

    #[Route(path: '/demo', name: 'app_demo_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('tag_demo/index.html.twig', [
            'examples' => self::EXAMPLES,
        ]);
    }

    #[Route(path: '/demo/tags/{slug}', name: 'app_demo_tags', methods: ['GET', 'POST'], requirements: ['slug' => '[a-z0-9\-]+'])]
    public function tags(Request $request, string $slug): Response
    {
        if (!isset(self::EXAMPLES[$slug])) {
            throw $this->createNotFoundException(sprintf('Unknown demo: %s', $slug));
        }

        $cfg = self::EXAMPLES[$slug];

        $form = $this->createForm(TagDemoType::class, ['tags' => $cfg['initial']], [
            'tag_options' => $cfg['options'],
            'tag_help'    => $cfg['help'],
        ]);

        $form->handleRequest($request);

        $submittedValue = null;
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var array{tags: array<int, string>|string} $data */
            $data           = $form->getData();
            $submittedValue = $data['tags'];
        }

        return $this->render('tag_demo/show.html.twig', [
            'form'            => $form,
            'submitted_value' => $submittedValue,
            'demo_title'      => $cfg['title'],
            'demo_slug'       => $slug,
            'examples'        => self::EXAMPLES,
        ]);
    }
}
