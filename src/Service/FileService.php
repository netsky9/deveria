<?php

namespace App\Service;

class FileService
{
    public function getFileName($imageFile): string
    {
        $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);

        return $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();
    }
}