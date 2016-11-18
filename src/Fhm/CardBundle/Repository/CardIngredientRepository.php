<?php
namespace Fhm\CardBundle\Repository;

use Fhm\FhmBundle\Repository\FhmRepository;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\UnitOfWork;
use Doctrine\ODM\MongoDB\Mapping\ClassMetadata;

/**
 * CardIngredientRepository
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CardIngredientRepository extends FhmRepository
{
    /**
     * Constructor
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
        return $this->createQueryBuilder()
            ->field('default')->equals(true)
            ->field('active')->equals(true)
            ->field('delete')->equals(false)
            ->getQuery()
            ->execute()
            ->toArray();
    }

    /**
     * @param        $card
     * @param string $grouping
     *
     * @return mixed
     * @throws \Doctrine\ODM\MongoDB\MongoDBException
     */
    public function getByCard(\Fhm\CardBundle\Document\Card $card, $grouping = "")
    {
        $builder = $this->createQueryBuilder();
        // Parent
        if($this->parent)
        {
            $builder->field('parent')->in(array('0', null));
        }
        // Language
        if($this->language)
        {
            $builder->field('languages')->in((array) $this->language);
        }
        // Grouping
        if($grouping != "")
        {
            $builder->addAnd(
                $builder->expr()
                    ->addOr($builder->expr()->field('grouping')->in((array) $grouping))
                    ->addOr($builder->expr()->field('global')->equals(true))
            );
        }
        // Card
        if($card->getIngredients()->count() > 0)
        {
            $ids = array();
            foreach($card->getIngredients() as $ingredient)
            {
                $ids[] = $ingredient->getId();
            }
            $builder->field('id')->in($ids);
        }
        else
        {
            return array();
        }
        // Common
        $builder->field('card.id')->equals($card->getId());
        $builder->field('active')->equals(true);
        $builder->field('delete')->equals(false);
        $this->builderSort($builder);

        return $builder
            ->getQuery()
            ->execute()
            ->toArray();
    }

    /**
     * @param        $card
     * @param string $grouping
     *
     * @return mixed
     * @throws \Doctrine\ODM\MongoDB\MongoDBException
     */
    public function getByCardAll(\Fhm\CardBundle\Document\Card $card, $grouping = "")
    {
        $builder = $this->createQueryBuilder();
        // Parent
        if($this->parent)
        {
            $builder->field('parent')->in(array('0', null));
        }
        // Language
        if($this->language)
        {
            $builder->field('languages')->in((array) $this->language);
        }
        // Grouping
        if($grouping != "")
        {
            $builder->addAnd(
                $builder->expr()
                    ->addOr($builder->expr()->field('grouping')->in((array) $grouping))
                    ->addOr($builder->expr()->field('global')->equals(true))
            );
        }
        // Card
        if($card->getIngredients()->count() > 0)
        {
            $ids = array();
            foreach($card->getIngredients() as $ingredient)
            {
                $ids[] = $ingredient->getId();
            }
            $builder->field('id')->in($ids);
        }
        else
        {
            return array();
        }
        // Common
        $builder->field('card.id')->equals($card->getId());
        $this->builderSort($builder);

        return $builder
            ->getQuery()
            ->execute()
            ->toArray();
    }

    /**
     * @param \Fhm\CardBundle\Document\Card        $card
     * @param \Fhm\CardBundle\Document\CardProduct $product
     * @param string                               $grouping
     *
     * @return mixed
     * @throws \Doctrine\ODM\MongoDB\MongoDBException
     */
    public function getByProduct(\Fhm\CardBundle\Document\Card $card, \Fhm\CardBundle\Document\CardProduct $product, $grouping = "")
    {
        $builder = $this->createQueryBuilder();
        // Parent
        if($this->parent)
        {
            $builder->field('parent')->in(array('0', null));
        }
        // Language
        if($this->language)
        {
            $builder->field('languages')->in((array) $this->language);
        }
        // Grouping
        if($grouping != "")
        {
            $builder->addAnd(
                $builder->expr()
                    ->addOr($builder->expr()->field('grouping')->in((array) $grouping))
                    ->addOr($builder->expr()->field('global')->equals(true))
            );
        }
        // Ingredients
        if($product->getIngredients()->count() > 0)
        {
            $ids = array();
            foreach($product->getIngredients() as $ingredient)
            {
                $ids[] = $ingredient->getId();
            }
            $builder->field('id')->in($ids);
        }
        else
        {
            return array();
        }
        // Common
        $builder->field('card.id')->equals($card->getId());
        $builder->field('active')->equals(true);
        $builder->field('delete')->equals(false);
        $this->builderSort($builder);

        return $builder
            ->getQuery()
            ->execute()
            ->toArray();
    }

    /**
     * @param \Fhm\CardBundle\Document\Card $card
     * @param string                        $grouping
     *
     * @return mixed
     * @throws \Doctrine\ODM\MongoDB\MongoDBException
     */
    public function getFormCard(\Fhm\CardBundle\Document\Card $card, $grouping = "")
    {
        $builder = $this->createQueryBuilder();
        // Parent
        if($this->parent)
        {
            $builder->field('parent')->in(array('0', null));
        }
        // Language
        if($this->language)
        {
            $builder->field('languages')->in((array) $this->language);
        }
        // Grouping
        if($grouping != "")
        {
            $builder->addAnd(
                $builder->expr()
                    ->addOr($builder->expr()->field('grouping')->in((array) $grouping))
                    ->addOr($builder->expr()->field('global')->equals(true))
            );
        }
        // Common
        $builder->field('card.id')->equals($card->getId());
        $builder->field('delete')->equals(false);
        $this->builderSort($builder);

        return $builder;
    }
}