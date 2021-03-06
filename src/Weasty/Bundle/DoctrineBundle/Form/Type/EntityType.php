<?php

namespace Weasty\Bundle\DoctrineBundle\Form\Type;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bridge\Doctrine\Form\ChoiceList\EntityLoaderInterface;
use Symfony\Bridge\Doctrine\Form\Type\DoctrineType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Weasty\Bundle\DoctrineBundle\Form\ChoiceList\EntityLoader;
use Weasty\Bundle\DoctrineBundle\Form\DataTransformer\IdToEntityTransformer;

/**
 * Class EntityType
 *
 * @package Weasty\Bundle\DoctrineBundle\Form\Type
 */
class EntityType extends DoctrineType {

  /**
   * @var string
   */
  protected $entityClass;

  /**
   * @inheritdoc
   */
  public function configureOptions( OptionsResolver $resolver ) {

    parent::configureOptions( $resolver );

    $resolver->setDefaults(
      [
        'map_as_id' => false,
        'class'     => $this->getEntityClass(),
      ]
    );

  }

  /**
   * @param FormBuilderInterface $builder
   * @param array                $options
   */
  public function buildForm( FormBuilderInterface $builder, array $options ) {

    parent::buildForm( $builder, $options );

    if( $options[ 'map_as_id' ] ) {

      $builder->addModelTransformer( new IdToEntityTransformer( $this->registry->getManager(), $options[ 'class' ] ) );

    }

  }

  /**
   * Return the default loader object.
   *
   * @param ObjectManager $manager
   * @param mixed         $queryBuilder
   * @param string        $class
   *
   * @return EntityLoaderInterface
   */
  public function getLoader( ObjectManager $manager, $queryBuilder, $class ) {

    $repository = $manager->getRepository( $class ?: $this->getEntityClass() );

    return new EntityLoader( $queryBuilder, $repository );

  }

  /**
   * Returns the name of this type.
   *
   * @return string The name of this type
   */
  public function getName() {

    return 'weasty_doctrine_entity_type';
  }

  /**
   * @return string
   */
  public function getEntityClass() {

    return $this->entityClass;
  }

  /**
   * @param string $entityClass
   */
  public function setEntityClass( $entityClass ) {

    $this->entityClass = $entityClass;
  }

}