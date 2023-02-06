<?php

namespace App\Twig\Runtime;

use App\Entity\Chuckle;
use App\Repository\GiggleRepository;
use Twig\Extension\RuntimeExtensionInterface;

class ChuckleExtensionRuntime implements RuntimeExtensionInterface
{
    public function __construct(private readonly GiggleRepository $giggleRepository)
    {
    }

    public function countGiggles(Chuckle $chuckle): int
    {
        return $this->giggleRepository->getGigglesFor($chuckle);
    }
}
