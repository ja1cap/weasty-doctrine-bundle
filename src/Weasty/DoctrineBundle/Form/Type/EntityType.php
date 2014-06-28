<?php
namespace Weasty\Bundle\DoctrineBundle\Form\Type;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bridge\Doctrine\Form\ChoiceList\EntityLoaderInterface;
use Symfony\Bridge\Doctrine\Form\Type\DoctrineType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Weasty\Bundle\DoctrineBundle\Form\ChoiceList\EntityLoader;

/**
 * Class EntityType
 * @package Weasty\Bundle\DoctrineBundle\Form\Type
 */
class EntityType extends DoctrineType {

    /**
     * @var string
     */
    protected $entityClass;

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {

        parent::setDefaultOptions($resolver);

        $resolver
            ->setDefaults(array(
                'class' => $this->getEntityClass(),
            ))
        ;

    }

    /**
     * Return the default loader object.
     *
     * @param ObjectManager $manager
     * @param mixed $queryBuilder
     * @param string $class
     *
     * @return EntityLoaderInterface
     */
    public function getLoader(ObjectManager $manager, $queryBuilder, $class)
    {

        $repository = $manager->getRepository($class ?: $this->getEntityClass());
        return new EntityLoader($queryBuilder, $repository);

    }

    /**
     * @param string $entityClass
     */
    public function setEntityClass($entityClass)
    {
        $this->entityClass = $entityClass;
    }

    /**
     * @return string
     */
    public function getEntityClass()
    {
        return $this->entityClass;
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'weasty_doctrine_entity_type';
    }

}