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

class PageBlockFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', 'integer', array('description' => 'Page block id'))
            ->add('type', 'text', array('description' => 'Page block type'))
            ->add('data', 'text', array('description' => 'Page block data'))
            ->add('position', 'integer', array('description' => 'Page block position'))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Engage360d\Bundle\PagesBundle\Entity\Page\PageBlock',
        ));
    }

    public function getName()
    {
        return 'engage360d_pages_page_block';
    }
}
