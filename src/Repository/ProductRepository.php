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
     * @param array         $filters
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

        //dump(array_values($filters['form']['seasons']));
        $qb = $this->createQueryBuilder('p');
        //foreach ($filters['form']['seasons'] as $season) {
        //    $qb->andWhere('p.season = :val');
        //    $qb->setParameter('val', $season);
        //}
        foreach ($filters['form']['categories'] as $category) {
            dump($category);
            $qb->orWhere($qb->expr()->orX(
                $qb->expr()->in('p.category', $category)
            ));
        }
        
        //if($filters['form']['minPrice'] == !null){
        //    dump('passe2');
        //    $qb->andWhere('p.price >= :val');
        //    $qb->setParameter('val', $filters['form']['minPrice']);
        //}
        //if($filters['form']['maxPrice'] == !null){
        //    dump('passe1');
        //    $qb->andWhere('p.price <= :val');
        //    $qb->setParameter('val', $filters['form']['maxPrice']);
        //}
        
        $qb->orderBy('p.createdAt', 'DESC');
        
        $query = $qb->getQuery();
        dump($qb);
        //dd($query = $qb->getQuery()->getResult());
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
