<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageUrlService
{
    private $requestStack;
    private $uploadDir;

    public function __construct(RequestStack $requestStack, string $uploadDir)
    {
        $this->requestStack = $requestStack;
        $this->uploadDir = $uploadDir;
    }

    public function uploadImage(UploadedFile $file, string $folder = ''): string
    {
        $safeFolder = trim($folder, '/');
        $targetDir = $this->uploadDir . ($safeFolder ? "/$safeFolder" : '');

        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0775, true);
        }

        $filename = uniqid() . '.' . $file->guessExtension();

        $file->move($targetDir, $filename);

        return $this->getFileUrl(($safeFolder ? "$safeFolder/" : '') . $filename);
    }


    public function getFileUrl(string $relativePath): string
    {
        $request = $this->requestStack->getCurrentRequest();
        return $request->getSchemeAndHttpHost() . '/Medias/' . ltrim($relativePath, '/');
    }
}
