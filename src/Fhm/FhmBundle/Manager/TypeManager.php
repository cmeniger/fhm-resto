<?php
/**
 * Created by PhpStorm.
 * User: fhm
 * Date: 17/01/17
 * Time: 16:52
 */
namespace Fhm\FhmBundle\Manager;

use Doctrine\Bundle\MongoDBBundle\Form\Type\DocumentType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

/**
 * Class TypeManager
 * @package Fhm\FhmBundle\Manager
 */
final class TypeManager
{
    /**
     * @var array
     */
    private static $types = array(
        'odm' => DocumentType::class,
        'orm' => EntityType::class,
    );

    /**
     * @param $class
     *
     * @return mixed
     */
    public static function getType($class)
    {
        if (!self::isValidType($class)) {
            return $class;
        }

        return self::$types[$class];
    }

    /**
     * @param $class
     * @return bool
     */
    public static function isValidType($class)
    {
        return isset(self::$types[$class]);
    }
}