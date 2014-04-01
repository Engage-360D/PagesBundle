<?php

/**
 * This file is part of the Engage360d package bundles.
 *
 */

namespace Engage360d\Bundle\PagesBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class CategoryFormType extends AbstractType
{
    private $class;

    public function __construct($class)
    {
        $this->class = $class;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array('description' => 'Category name'))
            ->add('url', 'text', array('description' => 'Category url'))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->class,
        ));
    }

    public function getName()
    {
        return 'engage360d_pages_category';
    }
}
