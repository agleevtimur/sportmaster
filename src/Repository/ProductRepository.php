<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
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
     * @param Product[] $products
     */
    public function findWithStores(array $products)
    {
        $sdks = array_map(fn($product) => $product->getSdk(), $products);
        $qb = $this->createQueryBuilder('p');
        $exp = $qb->expr()->in('p.sdk', $sdks);

        $query = $qb
            ->select('p.sdk, sp.store')
            ->innerJoin('p.storeProduct', 'sp')
            ->getQuery();
        $sql = $query->getSQL();
        return $query->getResult();
    }
}
