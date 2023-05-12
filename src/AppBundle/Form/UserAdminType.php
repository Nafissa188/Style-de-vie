<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;


class UserAdminType extends AbstractType
{
  protected $user;
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
      $this->user = $options['user'];
      $builder
          ->add('email', EmailType::class, array('label' => 'form.email', 'translation_domain' => 'FOSUserBundle'))
          ->add('username', TextType::class, array('label' => 'form.username', 'translation_domain' => 'FOSUserBundle','constraints' => [new NotBlank()]))
          ->add('plainPassword', RepeatedType::class, array(
              'type' => PasswordType::class,
              'options' => array(
                  'translation_domain' => 'FOSUserBundle',
                  'attr' => array(
                      'autocomplete' => 'new-password',
                  ),
              ),
              'first_options' => array('label' => 'form.password'),
              'second_options' => array('label' => 'form.password_confirmation'),
              'invalid_message' => 'fos_user.password.mismatch',
              'required' => true,
          ))
          ->add('firstName' , null , [
            'label'=>'Prénom'
          ])
          ->add('lastName', null , [
            'label'=>'Nom'
          ])
          ->add('phone' , null , [
            'label'=>'Téléphone'
          ])
          ->add('cin')
          ->add('enabled', CheckboxType::class, [
              'label'    => 'Activer le compte ?',
              'required' => false,
          ])
      ;
  }
/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'user' => null,
            'data_class' => 'AppBundle\Entity\User',
            'constraints' => [
                new UniqueEntity(['fields' => ['email']]),
                new UniqueEntity(['fields' => ['username']])
            ],
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_user';
    }


}
