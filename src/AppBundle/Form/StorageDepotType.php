<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class StorageDepotType extends AbstractType
{
    protected $user;
    protected $services;
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
      $this->user = $options['user'];
      $this->services = $options['services'];
        $builder

        ->add('depotName', TextType::class , [
          'label'=>'Nom du dépôt'
        ])
        ->add('phone', TextType::class , [
          'label'=>'Téléphone'
        ])
        ->add('address', TextType::class , [
          'label'=>'Adresse'
        ])
        ->add('altitude', TextType::class , [
          'label'=>'Latitude'
        ])
        ->add('longitude', TextType::class , [
          'label'=>'Longitude'
        ]);
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'services' => null,
            'user' => null,
            'data_class' => 'AppBundle\Entity\StorageDepot'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_storage_depot';
    }


}
