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

class PageFormType extends AbstractType
{
    private $class;

    public function __construct($class)
    {
        $this->class = $class;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', 'text', array('description' => 'Page title'))
            ->add('active', 'checkbox', array('description' => 'Page active status'))
            ->add('seoTitle', 'text', array('description' => 'Page seo title'))
            ->add('seoKeywords', 'text', array('description' => 'Page seo keywords'))
            ->add('seoDescription', 'text', array('description' => 'Page seo description'))
            ->add('url', 'text', array('description' => 'Page url'))
            ->add('category', 'entity', array(
                'class' => 'Engage360dPagesBundle:Category\Category',
                'property' => 'name'
            ))
            ->add('blocks', 'collection', array(
                'type'      => new PageBlockFormType(),
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            ))
            ->add('main', 'checkbox', array(
                'description' => 'Page is main for category',
                'required' => false,
            ))
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
        return 'engage360d_pages_page';
    }
}
