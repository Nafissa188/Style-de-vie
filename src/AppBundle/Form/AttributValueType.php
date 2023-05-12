<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class AttributValueType extends AbstractType
{
    protected $type;
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->user = $options['type'];
        if($options['type'] == 'color'){
            $builder->add('value', ColorType::class);
        }else{
            $builder->add('value');
        }
        

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event ) {

            $form = $event->getForm();
            $type = $this->type;
  
        });
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'type' => null,
            'data_class' => 'AppBundle\Entity\AttributValue'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_attribut_value';
    }


}
