<?php

namespace App\Service;

use App\Entity\Contest;
use App\Repository\WorkRepository;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final class ImageService
{
    public function __construct(
        #[Autowire('%image_dir%')] private readonly string $imageDir
    )
    {
    }

    public function upload(UploadedFile $uploadedFile): string
    {
        $filename = bin2hex(random_bytes(8)) . '.' . $uploadedFile->guessExtension();
        $uploadedFile->move($this->imageDir, $filename);
        return $filename;
    }

    //public function getPath(string $filename, ): string
}