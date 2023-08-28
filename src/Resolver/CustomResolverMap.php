<?php

namespace App\GraphQL\Resolver;

use App\Service\MutationService;
use App\Service\QueryService;
use ArrayObject;
use GraphQL\Type\Definition\ResolveInfo;
use Overblog\GraphQLBundle\Definition\ArgumentInterface;
use Overblog\GraphQLBundle\Resolver\ResolverMap;

class CustomResolverMap extends ResolverMap
{
    private QueryService $queryService;
    private MutationService $mutationService;

    public function __construct(
        QueryService    $queryService,
        MutationService $mutationService
    ) {
        $this->queryService = $queryService;
        $this->mutationService = $mutationService;
    }

    /**
     * @inheritDoc
     */
    protected function map(): array
    {
        return [
                'RootQuery'    => [
                        self::RESOLVE_FIELD => function (
                                $value,
                                ArgumentInterface $args,
                                ArrayObject $context,
                                ResolveInfo $info
                        ) {
                            switch ($info->fieldName) {
                                case 'author':
                                    return $this->queryService->findAuthor((int)$args['id']);
                                case 'authors':
                                    return $this->queryService->findAllAuthors();
                                case 'books':
                                    return $this->queryService->findAllBooks();
                                case 'book':
                                    return $this->queryService->findBookById((int)$args['id']);

                            }
                        },
                ],
                'RootMutation' => [
                        self::RESOLVE_FIELD => function (
                                $value,
                                ArgumentInterface $args,
                                ArrayObject $context,
                                ResolveInfo $info
                        ) {

                            switch ($info->fieldName) {
                                case 'createAuthor':
                                    return $this->mutationService->createAuthor($args['author']);
                                case 'updateAuthor':
                                    return $this->mutationService->updateAuthor((int)$args['id'], $args['author']);
                                case 'deleteAuthor':
                                    return $this->mutationService->deleteAuthor((int)$args['id']);
                                case 'createBook':
                                    return $this->mutationService->createBook($args['book']);
                                case 'updateBook':
                                    return $this->mutationService->updateBook((int)$args['id'], $args['book']);
                                case 'deleteBook':
                                    return $this->mutationService->deleteBook((int)$args['id']);
                            }
                        },
                ],
        ];
    }
}