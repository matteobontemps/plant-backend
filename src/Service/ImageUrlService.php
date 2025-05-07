<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\RequestStack;

class ImageUrlService
{
    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function getFileUrl(string $filename): string
    {
        $request = $this->requestStack->getCurrentRequest();
        return $request->getSchemeAndHttpHost() . '/Medias/' . $filename;
    }
}