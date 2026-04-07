<?php
declare(strict_types=1);

namespace Webwirkung\VibraplastTheme\Twig;

use Symfony\Component\String\Slugger\SluggerInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class SlugExtension extends AbstractExtension
{
    public function __construct(private SluggerInterface $slugger)
    {
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('wwSlug', [$this, 'slugify']),
        ];
    }

    public function slugify(string $value): string
    {
        return strtolower($this->slugger->slug($value)->toString());
    }
}
