<?php
namespace Fhm\CardBundle\Document\Repository;

use Fhm\FhmBundle\Document\Repository\FhmRepository;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\UnitOfWork;
use Doctrine\ODM\MongoDB\Mapping\ClassMetadata;

/**
 * CardProductRepository
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CardProductRepository extends FhmRepository
{
    /**
     * CardProductRepository constructor.
     * @param DocumentManager $dm
     * @param UnitOfWork $uow
     * @param ClassMetadata $class
     */
    public function __construct(DocumentManager $dm, UnitOfWork $uow, ClassMetadata $class)
    {
        parent::__construct($dm, $uow, $class);
    }

    /**
     * @return mixed
     */
    public function getDefault()
    {
        return $this->createQueryBuilder()->field('default')->equals(true)->field('active')->equals(true)->field(
            'delete'
        )->equals(false)->getQuery()->execute()->toArray();
    }

    /**
     * @param        $card
     *
     * @return mixed
     * @throws \Doctrine\ODM\MongoDB\MongoDBException
     */
    public function getByCard(\Fhm\CardBundle\Document\Card $card)
    {
        $builder = $this->createQueryBuilder();
        // Parent
        if ($this->parent) {
            $builder->field('parent')->in(array('0', null));
        }
        // Card
        if ($card->getProducts()->count() > 0) {
            $ids = array();
            foreach ($card->getProducts() as $product) {
                $ids[] = $product->getId();
            }
            $builder->field('id')->in($ids);
        } else {
            return array();
        }
        // Common
        $builder->field('card.id')->equals($card->getId());
        $builder->field('active')->equals(true);
        $builder->field('delete')->equals(false);
        $this->builderSort($builder);

        return $builder->getQuery()->execute()->toArray();
    }

    /**
     * @param        $card
     *
     * @return mixed
     * @throws \Doctrine\ODM\MongoDB\MongoDBException
     */
    public function getByCardAll(\Fhm\CardBundle\Document\Card $card)
    {
        $builder = $this->createQueryBuilder();
        // Parent
        if ($this->parent) {
            $builder->field('parent')->in(array('0', null));
        }
        // Card
        if ($card->getProducts()->count() > 0) {
            $ids = array();
            foreach ($card->getProducts() as $product) {
                $ids[] = $product->getId();
            }
            $builder->field('id')->in($ids);
        } else {
            return array();
        }
        // Common
        $builder->field('card.id')->equals($card->getId());
        $this->builderSort($builder);

        return $builder->getQuery()->execute()->toArray();
    }

    /**
     * @param \Fhm\CardBundle\Document\Card $card
     * @param string $search
     *
     * @return array
     * @throws \Doctrine\ODM\MongoDB\MongoDBException
     */
    public function getByCardSearch(\Fhm\CardBundle\Document\Card $card, $search = "")
    {
        $builder = $search ? $this->search($search) : $this->createQueryBuilder();
        // Parent
        if ($this->parent) {
            $builder->field('parent')->in(array('0', null));
        }
        // Card
        if ($card->getProducts()->count() > 0) {
            $ids = array();
            foreach ($card->getProducts() as $product) {
                $ids[] = $product->getId();
            }
            $builder->field('id')->in($ids);
        } else {
            return array();
        }
        // Common
        $builder->field('card.id')->equals($card->getId());
        $this->builderSort($builder);

        return $builder->getQuery()->execute()->toArray();
    }

    /**
     * @param \Fhm\CardBundle\Document\Card $card
     * @param \Fhm\CardBundle\Document\CardCategory $category
     *
     * @return array
     * @throws \Doctrine\ODM\MongoDB\MongoDBException
     */
    public function getByCategory(
        \Fhm\CardBundle\Document\Card $card,
        \Fhm\CardBundle\Document\CardCategory $category
    ) {
        $builder = $this->createQueryBuilder();
        // Parent
        if ($this->parent) {
            $builder->field('parent')->in(array('0', null));
        }

        // Products
        if ($category->getProducts()->count() > 0) {
            $ids = array();
            foreach ($category->getProducts() as $product) {
                $ids[] = $product->getId();
            }
            $builder->field('id')->in($ids);
        } else {
            return array();
        }
        // Common
        $builder->field('card.id')->equals($card->getId());
        $builder->field('active')->equals(true);
        $builder->field('delete')->equals(false);
        $this->builderSort($builder);

        return $builder->getQuery()->execute()->toArray();
    }

    /**
     * @param \Fhm\CardBundle\Document\Card $card
     * @param \Fhm\CardBundle\Document\CardCategory $category
     *
     * @return array
     * @throws \Doctrine\ODM\MongoDB\MongoDBException
     */
    public function getByCategoryAll(
        \Fhm\CardBundle\Document\Card $card,
        \Fhm\CardBundle\Document\CardCategory $category
    ) {
        $builder = $this->createQueryBuilder();
        // Parent
        if ($this->parent) {
            $builder->field('parent')->in(array('0', null));
        }

        // Products
        if ($category->getProducts()->count() > 0) {
            $ids = array();
            foreach ($category->getProducts() as $product) {
                $ids[] = $product->getId();
            }
            $builder->field('id')->in($ids);
        } else {
            return array();
        }
        // Common
        $builder->field('card.id')->equals($card->getId());
        $this->builderSort($builder);

        return $builder->getQuery()->execute()->toArray();
    }

    /**
     * @param \Fhm\CardBundle\Document\Card $card
     *
     * @return mixed
     * @throws \Doctrine\ODM\MongoDB\MongoDBException
     */
    public function getFormCard(\Fhm\CardBundle\Document\Card $card)
    {
        $builder = $this->createQueryBuilder();
        // Parent
        if ($this->parent) {
            $builder->field('parent')->in(array('0', null));
        }

        // Common
        $builder->field('card.id')->equals($card->getId());
        $builder->field('delete')->equals(false);
        $this->builderSort($builder);

        return $builder;
    }
}