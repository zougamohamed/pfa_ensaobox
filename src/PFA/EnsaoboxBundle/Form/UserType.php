<?php

namespace PFA\EnsaoboxBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('prenom');
        $builder->add('dateNaissance','date', array(
            'years' => range(date('Y') -30, date('Y')-15),
        ));
        $builder->add('lienLinkedin','text', array('required' => false));
        $builder->add('lienFacebook','text', array('required' => false));
        $builder->add('aPropos', 'text', array('required' => false));
        $builder->add('Enregistrer','submit');
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PFA\EnsaoboxBundle\Entity\User'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'pfa_ensaoboxbundle_user';
    }
}
