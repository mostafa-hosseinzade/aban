<?php

namespace ForumBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GroupForumType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('meta')
            ->add('orderList')
            ->add('enabled')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
   public function configureOptions(OptionsResolver $resolver) 
    {
        $resolver->setDefaults(array(
            'data_class' => 'ForumBundle\Entity\GroupForum',
            'csrf_protection' => false
        ));
    }

    /**
     * @return string
    */
    public function getName()
    {
        return 'groupforum';
    }
}
