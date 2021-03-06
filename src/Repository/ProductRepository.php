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
            ->where('a.enabled = true')
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
        $qb = $this->createQueryBuilder('p');

        if($filters->getSearch() == !null){
            $qb->where($qb->expr()->like('p.name', ':search'))->setParameter('search', "%" . $filters->getSearch() . "%");
        }
        if(($filters->getSeasons() == !null ) && ($filters->getSearch() == null)){
            foreach ($filters->getSeasons() as $season) {
                $qb->andWhere($qb->expr()->orX(
                    $qb->expr()->in('p.season', ':season')
                ))->setParameter('season', $season->getId());
            }
        }
        if(($filters->getCategories() == !null) && ($filters->getSearch() == null)){
            foreach ($filters->getCategories() as $category) {
                $qb->andWhere($qb->expr()->orX(
                    $qb->expr()->in('p.category', $category->getId())
                ));
            }
        }
        if($filters->getMinPrice() == !null){
            $qb->andWhere('p.price >= :valMin');
            $qb->setParameter('valMin', $filters->getMinPrice());
        }
        if($filters->getMaxPrice() == !null){
            $qb->andWhere('p.price <= :valMax');
            $qb->setParameter('valMax', $filters->getMaxPrice());
        }

        return $this->paginatorDeploy($page, $nbMaxByPage, $qb);
    }

    /**
     * @param Filter||Search $filters Entity or Search Entity
     * @param int           $page Actual page
     * @param int           $nbMaxByPage Products by page
     * @param bool          $enabled
     * @return Product[]    Returns an array of Product objects
     */
    public function findBySearchAndPaginator($filters, $page, $nbMaxByPage, $enabled = true)
    {
        $qb = $this->createQueryBuilder('p');

        if($filters->getSearch() == !null){
            $qb->where($qb->expr()->like('p.name', ':search'))->setParameter('search', "%" . $filters->getSearch() . "%");
        }
        
        return $this->paginatorDeploy($page, $nbMaxByPage, $qb, $enabled);
    }

    /**
     * @param int           $page
     * @param int           $nbMaxByPage
     * @param bool          $enabled
     * @return Paginator    $paginator
     */
    public function findAllAndPaginator($page, $nbMaxByPage, $enabled = true)
    {
        return $this->paginatorDeploy($page, $nbMaxByPage, null, $enabled);
    }

    /**
     * @param int           $page
     * @param int           $nbMaxByPage
     * @param Qb|null       $qb
     * @param bool          $enabled
     * @return Paginator    $paginator
     */
    private function paginatorDeploy($page, $nbMaxByPage, $qb = null, $enabled = true)
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
        
        if($qb == null){
            $qb = $this->createQueryBuilder('p');
        }

        if($enabled == true){
            $qb->andWhere('p.enabled = :enabled');
            $qb->setParameter('enabled', true);
        }

        $query = $qb->orderBy('p.createdAt', 'DESC')->getQuery();
        $firstResult = ($page - 1) * $nbMaxByPage;
        $query->setFirstResult($firstResult)->setMaxResults($nbMaxByPage);
        $paginator = new Paginator($query);

        if ( ($paginator->count() <= $firstResult) && $page != 1) {
            throw new NotFoundHttpException('La page demandée n\'existe pas.'); // page 404, sauf pour la première page
        }

        return $paginator;
    }

    /**
      * @return int Return length of product table
      */
    public function productLenght()
    {
        return $this->createQueryBuilder('s')
            ->select('count(s.id)')
            ->getQuery()
            ->getSingleScalarResult()
        ;
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
