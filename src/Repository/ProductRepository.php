<?php

namespace App\Repository;

use App\Constants\Constants;
use App\Entity\Product;
use App\Entity\Specification;
use App\Form\Model\ProductSearchDto;
use App\Helpers\ProductTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use DateTime;
use Doctrine\ORM\Query\Expr\Join;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    use ProductTrait;

    /** @var PaginatorInterface $pagination */
    private $pagination;

    /**
     * @param ManagerRegistry $registry
     * @param PaginatorInterface $pagination
     */
    public function __construct(ManagerRegistry $registry, PaginatorInterface $pagination)
    {
        parent::__construct($registry, Product::class);

        $this->pagination = $pagination;
    }

    /**
     * @param $pId
     * @param $parentId
     * @param int $limit
     * @return int|mixed|string
     */
    public function findSimilar($pId, $parentId, int $limit = 12)
    {
        $entityManager = $this->getEntityManager();

        return $entityManager->createQuery(
            'SELECT e
            FROM App\Entity\Product e
            WHERE e.id <>:pId AND e.parentId <>:parentId'
        )->setParameters(['pId' => $pId, 'parentId' => $parentId])->setMaxResults($limit)->getResult();
    }

    /**
     * @param $attr
     * @return int|mixed|string|null
     */
    public function findOneByAttr($attr)
    {
        try {

            $entityManager = $this->getEntityManager();

            return $entityManager->createQuery(
                'SELECT e
            FROM App\Entity\Product e
            WHERE e.id =:attr OR e.slug =:attr'
            )->setParameter('attr', $attr)->getOneOrNullResult();
        } catch (\Exception $ex) {
        }

        return null;
    }

    /**
     * @param $slug
     * @param $pId
     * @return int|mixed|string|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOneBySlugId($slug, $pId)
    {
        $entityManager = $this->getEntityManager();

        return $entityManager->createQuery(
            'SELECT e
            FROM App\Entity\Product e
            WHERE e.id =:pId AND e.slug =:slug'
        )->setParameters(['pId' => $pId, 'slug' => $slug])->getOneOrNullResult();
    }

    /**
     * @param $pId
     * @param $parentId
     * @return int|mixed|string
     */
    public function findRelationship($pId, $parentId)
    {
        $entityManager = $this->getEntityManager();

        return $entityManager->createQuery(
            'SELECT e, b
            FROM App\Entity\Product e 
            LEFT JOIN e.brandId b
            WHERE e.parentId =:parentId AND e.id <>:pId
            ORDER BY e.id ASC'
        )->setParameters(['parentId' => $parentId, 'pId' => $pId])->getResult();
    }

    /**
     * @param Request $request
     * @return \Knp\Component\Pager\Pagination\PaginationInterface
     */
    public function filterProducts(Request $request): \Knp\Component\Pager\Pagination\PaginationInterface
    {
        $entityManager = $this->getEntityManager();

        $dql = 'SELECT e
            FROM App\Entity\Product e 
            LEFT JOIN e.brandId b
            LEFT JOIN e.productSubcategories psc
            LEFT JOIN psc.subCategory sc 
            LEFT JOIN sc.categoryId c ';

        $printWhere = true;
        if ($sc = $request->get('category')) {

            $dql = is_numeric($sc) ? $dql . 'WHERE (sc.id =:sc OR c.id =:sc) ' : $dql . 'WHERE (sc.slug =:sc OR c.slug =:sc) ';
            $printWhere = false;
        }

        if ($request->get('featured')) {
            $dql = $dql . ($printWhere ? 'WHERE (e.featured = TRUE) ' : 'AND (e.featured = TRUE) ');
            $printWhere = false;
        }

        if ($br = $request->get('filter_brand')) {
            $brandSlugs = $this->getExplodeBySeparator($br);

            if (count($brandSlugs) > 0 && $brandSlugs[0] != 'undefined') {
                $dql = $dql . ($printWhere ? 'WHERE (b.slug IN (:brandSlugs)) ' : 'AND (b.slug IN (:brandSlugs)) ');
                $printWhere = false;
            }
        }

        if ($colors = $request->get('filter_color')) {
            $colorSlugs = $this->getExplodeBySeparator($colors);

            if (count($colorSlugs) > 0 && $colorSlugs[0] != 'undefined') {
                $dql = $dql . ($printWhere ? 'WHERE (psf.customFieldsType =:color AND sp.slug IN (:colorSlugs)) ' : 'AND (psf.customFieldsType =:color AND sp.slug IN (:colorSlugs)) ');
                $printWhere = false;
            }
        }

        if ($rg = $request->get('filter_price')) {
            [$min, $max] = $this->getRange($rg);
            $dql = $dql . ($printWhere ? 'WHERE (e.price >=:min AND e.price <=:max) ' : 'AND (e.price >=:min AND e.price <=:max) ');
            $printWhere = false;
        }

        $fd = $request->get('filter_discount');
        if ($request->get('in_offer') || ($fd && $fd == 'yes')) {
            $dql = $dql . ($printWhere ? 'WHERE (e.offerPrice > 0) ' : 'AND (e.offerPrice > 0) ');
            $printWhere = false;
        }

        if (($tp = $request->get('top_rated')
                || $bs = $request->get('best_selling')
                || $na = $request->get('new_arrivals')
                || $oc = $request->get('ordering_criteria'))
            && !$printWhere
        ) {

            $printOrder = true;

            if (isset($tp) && $tp) {
                $dql = $dql . ' ORDER BY e.reviews DESC ';
                $printOrder = false;
            }

            if (isset($bs) && $bs) {
                $dql = $dql . ($printOrder ? ' ORDER BY e.sales DESC' : ', e.sales DESC ');
                $printOrder = false;
            }

            if (isset($na) && $na) {
                $dql = $dql . ($printOrder ? ' ORDER BY e.date DESC' : ', e.date DESC ');
                $printOrder = false;
            }

            if (isset($oc) && $oc) {
                [$attr, $order] = explode('_', $oc);

                $dql = $dql . ($printOrder
                    ? ' ORDER BY e.' . $attr . ' ' . strtoupper($order)
                    : ', e.' . $attr . ' ' . strtoupper($order));
            }
        }

        $query = $entityManager->createQuery($dql);

        $query = isset($sc) && $sc ? $query->setParameter('sc', $sc) : $query;

        $query = isset($br) && isset($brandSlugs) && count($brandSlugs) > 0 && $brandSlugs[0] != 'undefined'
            ? $query->setParameter('brandSlugs', $brandSlugs)
            : $query;

        $query = isset($colors) && isset($colorSlugs) && count($colorSlugs) > 0 && $colorSlugs[0] != 'undefined'
            ? $query->setParameter('color', 'color')->setParameter('colorSlugs', $colorSlugs)
            : $query;

        $query = isset($rg) && isset($min) && isset($max)
            ? $query->setParameter('min', $min)->setParameter('max', $max)
            : $query;

        return $this->pagination->paginate($query, $request->get('page', 1), $request->get('limit', 12));
    }

    /**
     * @param Request $request
     * @return \Knp\Component\Pager\Pagination\PaginationInterface
     */
    public function getParents(Request $request, ProductSearchDto $productSearchType): \Knp\Component\Pager\Pagination\PaginationInterface
    {
        $entityManager = $this->getEntityManager();
        $where = "";
        if ($productSearchType->getName()) {
            $where .= "and clearstr(e.name) like clearstr('%" . $productSearchType->getName() . "%')";
        }
        if ($productSearchType->getCategory()) {
            $where .= "and clearstr(c.name) like clearstr('%" . $productSearchType->getCategory() . "%')";
        }
        if ($productSearchType->getSubcategory()) {
            $where .= "and clearstr(sc.name) like clearstr('%" . $productSearchType->getSubcategory() . "%')";
        }
        if ($productSearchType->getState() == 'in-stock') {
            $where .= "and e.stock > 0";
        }
        if ($productSearchType->getState() == 'no-stock') {
            $where .= "and e.stock = 0";
        }

        $dql = "SELECT e
            FROM App\Entity\Product e 
            LEFT JOIN e.productSubcategories psc
            LEFT JOIN psc.subCategory sc
            LEFT JOIN sc.categoryId c
            where  e.parentId is null $where
            ORDER BY e.id DESC";
        $query = $entityManager->createQuery($dql);
        return $this->pagination->paginate($query, $request->get('page', 1), $request->get('limit', 15));
    }

    /**
     * @param int $parentId
     * @return Product[]
     */
    public function getChildrens($id): array
    {
        $entityManager = $this->getEntityManager();
        return $entityManager->createQuery('SELECT e
            FROM App\Entity\Product e 
            WHERE e.stock > 0
            and e.parentId =:id
            ORDER BY e.id DESC')->setParameter('id', $id)->getResult();
    }

    /**
     * @param $name
     * @param int $limit
     * @param string $slug
     * @return int|mixed|string
     */
    public function suggestionsProducts($name, int $limit = 5, string $slug = 'all')
    {
        $entityManager = $this->getEntityManager();

        $dql = 'SELECT e
            FROM App\Entity\Product e 
            LEFT JOIN e.brandId b
            LEFT JOIN e.productSubcategories psc
            LEFT JOIN psc.subCategory sc 
            LEFT JOIN sc.categoryId c ';

        $printWhere = true;
        if ($slug != 'all') {
            $dql = $dql . ' WHERE (sc.id =:sc OR c.id =:sc OR sc.slug =:sc OR c.slug =:sc) ';
            $printWhere = false;
        }

        $dql = $dql . ($printWhere ? 'WHERE e.name LIKE :name' : ' AND e.name LIKE :name');
        $dql = $dql . ' ORDER BY e.name DESC';

        $query = $entityManager->createQuery($dql)
            ->setParameter('name', '%' . $name . '%')
            ->setMaxResults($limit ?? 5);

        if ($slug != 'all') {
            $query->setParameter('sc', $slug);
        }

        return $query
            ->getResult();
    }

    /**
     * @return Product[] Returns an array of Product objects
     */
    public function list($limit = 10, $offset = 0)
    {
        $sql = "select 
                    max(p.id)as id,
                    max(p.parent_id)as parent_id,
                    max(p.image)as image,
                    max(p.name)as name,
                    max(p.price)as price,
                    bool_or(p.featured)as featured,
                    max(p.date)as date,
                    max(p.html_description)as html_description,
                    max(p.short_description)as short_description,
                    max(p.brand_id)as brand_id
                from mia_product
                p group by p.parent_id
                offset $offset limit $limit";

        $statement = $this->getEntityManager()->getConnection()->prepare($sql);
        $statement->executeQuery();
        // return $statement->fetchAll();
    }

    /**
     * @return Product[] Returns an array of Product objects
     */
    public function cant()
    {
        $sql = "select count(*) from 
        (select 
            max(p.id) as id            
        from mia_product p group by p.parent_id) as c";

        $statement = $this->getEntityManager()->getConnection()->prepare($sql);
        $statement->executeQuery();
        // return $statement->fetchAll();
    }


    /**
     * @return Product[] Returns an array of Product objects
     * @description obtener los productos nuevos(los que la fecha de entrada al sistema es mayor a la pasada por parametro)
     */
    public function findLast($date)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.date > :date')
            ->setParameter('date', $date)
            ->orderBy('p.date', 'DESC')
            ->getQuery()
            ->getResult();
    }

    //obtener los 50 mas vendidos
    // public function findMostSale()
    // {
    //     return $this->createQueryBuilder('p')
    //         ->orderBy('p.sales', 'DESC')
    //         ->getQuery()
    //         ->getMaxResults(50)
    //         ->getResult();
    // }

    //obtener los que estan en oferta---hacer
    public function findProductsInOffert()
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.offerPrice > 0')
            ->getQuery()
            ->getResult();
    }

    public function findFreeSku($sku, $product_id)
    {
        $skuResult = $this->createQueryBuilder('p')
            ->select('p.id')
            ->where('p.sku = :sku')
            ->setParameter('sku', $sku);
        if ($product_id) {
            $skuResult->andWhere('p.id != :product_id')
                ->setParameter('product_id', $product_id);
        }
        return $skuResult->getQuery()->getOneOrNullResult();;
    }

    public function findProductsToSendTo3pl(array $statuses, array $orders = null, int $limit = null): array
    {
        $product = $this->createQueryBuilder('p')
            ->where('p.status_sent_3pl IN (:statuses)')
            ->setParameter('statuses', $statuses);
        if ($orders) {
            foreach ($orders as $orderKey => $orderValue) {
                $product->orderBy('p.' . $orderKey, $orderValue);
            }
        }
        if ($limit) {
            $product->setMaxResults($limit);
        }
        return $product->getQuery()
            ->getResult();
    }

    public function findProductsVisibleByTag($tag, $category, $limit = 4, $index = 0)
    {

        $today = new DateTime();

        $products = $this->createQueryBuilder('p')
            ->where('p.tag = :tag')
            ->andWhere('p.id3pl IS NOT NULL')
            ->andWhere('p.category = :category')
            ->andWhere('p.visible = :visible')
            ->andWhere(
                'p.tag_expires = :tag_expires or p.tag_expiration_date > :today'
            )
            ->setParameter('tag', $tag)
            ->setParameter('category', $category)
            ->setParameter('visible', true)
            ->setParameter('tag_expires', false)
            ->setParameter('today', $today);
        if ($index) {
            $products->setFirstResult($index);
        }
        $products->setMaxResults($limit);
        $products->orderBy('RANDOM()');

        return $products->getQuery()
            ->getResult();
    }

    public function findProductByFilters($filters, $limit = 4, $index = 0, $keywords)
    {

        $today = new DateTime();

        $products = $this->createQueryBuilder('p');

        $products->where('p.visible = true');
        $products->andWhere('p.id3pl IS NOT NULL');
        if ($keywords) {
            $orX = $products->expr()->orX();
            foreach ($keywords as $keyword) {
                $orX->add(
                    $products->expr()->orX(
                        $products->expr()->like('p.name', "'%" . $keyword . "%'"),
                        $products->expr()->like('p.descriptionEs', "'%" . $keyword . "%'")
                    )
                );
            }
            $products->andWhere($orX);
        }

        $orX = $products->expr()->orX();
        foreach ($filters as $key => $filter) {
            if ($filter['method'] == 'LIKE') {
                foreach ($filter['parameters'] as $parametro) {
                    $orX->add(
                        $products->expr()->orX(
                            $products->expr()->like('p.' . $filter['column'], "'" . $parametro . "'")
                        )
                    );
                }
            } else if ($filter['method'] == 'IN') {
                foreach ($filter['parameters'] as $parametro) {
                    $orX->add(
                        $products->expr()->orX(
                            $products->expr()->eq('p.' . $filter['column'], $parametro->getId())
                        )
                    );
                }
            } else {
                foreach ($filter['parameters'] as $parametro) {
                    $orX->add(
                        $products->expr()->orX(
                            $products->expr()->eq('p.' . $filter['column'], $parametro)
                        )
                    );
                }
            }
        }

        $products->andWhere($orX);

        if ($index) {
            $products->setFirstResult($index);
        }
        $products->setMaxResults($limit);
        return $products->getQuery()->getResult();
    }

    public function findActiveProductById($product_id)
    {
        return $this->createQueryBuilder('p')
            ->where('p.visible = :visible')
            ->andWhere('p.id3pl IS NOT NULL')
            ->andWhere('p.id =:product_id')
            ->setParameter('visible', true)
            ->setParameter('product_id', $product_id)
            ->getQuery()
            ->getOneOrNullResult();
    }


    public function findSimilarProductBySku($sku, $product_id)
    {
        // recorto el sku para traer los productos que son similares por SKU
        $sku_recortado = substr($sku, 0, 24);
        return $this->createQueryBuilder('p')
            ->join(Specification::class, 'msp', Join::WITH, 'msp.id = p.storage')
            ->where('p.visible = :visible')
            ->andWhere('p.id3pl IS NOT NULL')
            ->andWhere('p.sku like :sku_recortado')
            // ->andWhere('p.id != :product_id')
            ->setParameter('visible', true)
            // ->setParameter('product_id', $product_id)
            ->setParameter('sku_recortado', $sku_recortado . '%')
            ->orderBy('msp.name','asc')
            ->getQuery()
            ->getResult();
    }

    public function findColorsAvailable($sku, $product_id)
    {
        //sku recortado hasta el color inclusive
        // $sku_recortado_color = substr($sku, 0, 24);
        //sku recortado solo categoria, marca y modelo-
        $sku_recortado = substr($sku, 0, 20);

        return $this->createQueryBuilder('p')
            ->select('max(p.id) as product_id,msp.name as color, msp.colorHexadecimal')
            ->join(Specification::class, 'msp', Join::WITH, 'msp.id = p.color')
            ->where('p.visible = :visible')
            ->andWhere('p.id3pl IS NOT NULL')
            ->andWhere('p.sku like :sku_recortado')
            // ->andWhere('p.sku not like :sku_recortado_color')
            //->andWhere('p.id != :product_id')
            ->setParameter('visible', true)
            //->setParameter('product_id', $product_id)
            ->setParameter('sku_recortado', $sku_recortado . '%')
            // ->setParameter('sku_recortado_color', $sku_recortado_color . '%')
            ->orderBy('msp.name', 'asc')
            ->groupBy('msp.name,msp.colorHexadecimal')
            ->getQuery()
            ->getResult();
    }
}

// tengo el id de producto, necesito productos 