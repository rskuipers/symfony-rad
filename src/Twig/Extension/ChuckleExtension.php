<?php

namespace App\Twig\Extension;

use App\Twig\Runtime\ChuckleExtensionRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ChuckleExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('giggles', [ChuckleExtensionRuntime::class, 'countGiggles']),
        ];
    }
}
