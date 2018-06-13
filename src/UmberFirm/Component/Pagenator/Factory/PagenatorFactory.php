<?php

declare(strict_types=1);

namespace UmberFirm\Component\Pagenator\Factory;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Hateoas\Representation\Factory\PagerfantaFactory;
use Hateoas\Representation\PaginatedRepresentation;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Psr\Log\InvalidArgumentException;
use Symfony\Component\HttpFoundation\RequestStack;
use UmberFirm\Component\Pagenator\Repository\RepositoryPagenatorInterface;
use Hateoas\Configuration\Route;

/**
 * Class PagenatorFactory
 *
 * @package UmberFirm\Component\Pagenator\Factory
 */
class PagenatorFactory implements PagenatorFactoryInterface
{
    /**
     * @var PagerfantaFactory
     */
    private $pagerfantaFactory;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @var string
     */
    private $searchQuery;

    /**
     * @var QueryBuilder
     */
    private $queryBuilder;

    /**
     * PagenatorFactory constructor.
     *
     * @param PagerfantaFactory $pagerfantaFactory
     * @param EntityManagerInterface $entityManager
     * @param RequestStack $requestStack
     */
    public function __construct(
        PagerfantaFactory $pagerfantaFactory,
        EntityManagerInterface $entityManager,
        RequestStack $requestStack
    ) {
        $this->pagerfantaFactory = $pagerfantaFactory;
        $this->entityManager = $entityManager;
        $this->requestStack = $requestStack;
    }

    /**
     * {@inheritdoc}
     */
    public function getRepresentation(int $limit = 10, int $currentPage = 1, array $parameters = []): PaginatedRepresentation
    {
        $pagerfanta = $this->createPagerfanta($limit, $currentPage);

        return $this->pagerfantaFactory->createRepresentation(
            $pagerfanta,
            new Route(
                $this->requestStack->getCurrentRequest()->get('_route'),
                array_merge(['q' => $this->searchQuery], $parameters),
                true
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function searchByQuery(string $entityClass, ?string $searchQuery): PagenatorFactoryInterface
    {
        /** @var RepositoryPagenatorInterface $repository */
        $repository = $this->entityManager->getRepository($entityClass);
        if (false === $repository instanceof RepositoryPagenatorInterface) {
            throw new InvalidArgumentException('Invalid repository used');
        }
        $this->searchQuery = $searchQuery;
        $this->queryBuilder = $repository->createSearchQueryBuilder($searchQuery);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getQueryBuilder(): QueryBuilder
    {
        return $this->queryBuilder;
    }

    /**
     * @param int $limit
     * @param int $currentPage
     *
     * @return Pagerfanta
     */
    private function createPagerfanta(int $limit = 10, int $currentPage = 1): Pagerfanta
    {
        $adapter = new DoctrineORMAdapter($this->queryBuilder, false);
        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta->setMaxPerPage($limit);
        $pagerfanta->setCurrentPage($currentPage);

        return $pagerfanta;
    }
}
