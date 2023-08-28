<?php

namespace App\Service;

class BookService
{
    private string $rootPublicPath;

    /**
     * @param string $rootPublicPath
     */
    public function __construct(string $rootPublicPath)
    {
        $this->rootPublicPath = $rootPublicPath;
    }

    function prepareImageLinks(array $books): array
    {
        foreach ($books as &$book) {
            if (isset($book['cover'])) {
                $book['cover'] = str_replace(
                        $this->rootPublicPath,
                        '',
                        $book['cover']
                );
            }
        }
        unset($book);

        return $books;
    }
}