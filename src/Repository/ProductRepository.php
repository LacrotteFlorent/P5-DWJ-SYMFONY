<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * @param int           $limit
     * @param string|null   $orderBy, DESC if null
     * @return Product[]    Returns an array of Product objects
     */
    public function findAllWithLimit($limit, $orderBy = 'DESC')
    {
        return $this->createQueryBuilder('a')
            ->orderBy('a.createdAt', $orderBy)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @param Filter        $filters Entity
     * @param int           $page Actual page
     * @param int           $nbMaxByPage Products by page
     * @return Product[]    Returns an array of Product objects
     */
    public function findByFiltersAndPaginator($filters, $page, $nbMaxByPage)
    {
        if (!is_numeric($page)) {
            throw new InvalidArgumentException(
                'La valeur de l\'argument $page est incorrecte (valeur : ' . $page . ').'
            );
        }

        if ($page < 1) {
            throw new NotFoundHttpException('La page demandée n\'existe pas');
        }

        if (!is_numeric($nbMaxByPage)) {
            throw new InvalidArgumentException(
                'La valeur de l\'argument $nbMaxByPage est incorrecte (valeur : ' . $nbMaxByPage . ').'
            );
        }

        $qb = $this->createQueryBuilder('p');
        foreach ($filters->getSeasons() as $season) {
            $qb->orWhere($qb->expr()->orX(
                $qb->expr()->in('p.season', $season->getId())
            ));
        }
        foreach ($filters->getCategories() as $category) {
            $qb->orWhere($qb->expr()->orX(
                $qb->expr()->in('p.category', $category->getId())
            ));
        }
        if($filters->getMinPrice() == !null){
            $qb->andWhere('p.price >= :valMin');
            $qb->setParameter('valMin', $filters->getMinPrice());
        }
        if($filters->getMaxPrice() == !null){
            $qb->andWhere('p.price <= :valMax');
            $qb->setParameter('valMax', $filters->getMaxPrice());
        }
        $qb->orderBy('p.createdAt', 'DESC');
        
        $query = $qb->getQuery();

        $firstResult = ($page - 1) * $nbMaxByPage;
        $query->setFirstResult($firstResult)->setMaxResults($nbMaxByPage);
        $paginator = new Paginator($query);

        if ( ($paginator->count() <= $firstResult) && $page != 1) {
            throw new NotFoundHttpException('La page demandée n\'existe pas.'); // page 404, sauf pour la première page
        }

        return $paginator;
    }

    /**
     * @param int           $page
     * @param int           $nbMaxByPage
     * @return Paginator    $paginator
     */
    public function findAllAndPaginator($page, $nbMaxByPage)
    {
        if (!is_numeric($page)) {
            throw new InvalidArgumentException(
                'La valeur de l\'argument $page est incorrecte (valeur : ' . $page . ').'
            );
        }

        if ($page < 1) {
            throw new NotFoundHttpException('La page demandée n\'existe pas');
        }

        if (!is_numeric($nbMaxByPage)) {
            throw new InvalidArgumentException(
                'La valeur de l\'argument $nbMaxByPage est incorrecte (valeur : ' . $nbMaxByPage . ').'
            );
        }

        $qb = $this->createQueryBuilder('p')
            ->orderBy('p.createdAt', 'DESC');
        
        $query = $qb->getQuery();
        $firstResult = ($page - 1) * $nbMaxByPage;
        $query->setFirstResult($firstResult)->setMaxResults($nbMaxByPage);
        $paginator = new Paginator($query);

        if ( ($paginator->count() <= $firstResult) && $page != 1) {
            throw new NotFoundHttpException('La page demandée n\'existe pas.'); // page 404, sauf pour la première page
        }

        return $paginator;
    }

    // /**
    //  * @return Product[] Returns an array of Product objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Product
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
