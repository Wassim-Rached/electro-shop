<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    private string $targetDirectory = 'uploads/';

    public function upload(UploadedFile $file): string
    {
        $newFileName = md5(uniqid()) . '.' . $file->guessExtension();
        $file->move($this->targetDirectory, $newFileName);

        return $newFileName;
    }
}