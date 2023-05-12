<?php

namespace ApiBundle\Form;

use FOS\UserBundle\Util\LegacyFormHelper;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Validator\Constraints\NotBlank;

class ChangePasswordFormType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {

      $builder->add('plainPassword', RepeatedType::class, array(
          'type' => PasswordType::class,
          'options' => array(
              'translation_domain' => 'FOSUserBundle',
              'attr' => array(
                  'autocomplete' => 'new-password',
              ),
          ),
          'first_options' => array('label' => 'form.new_password'),
          'second_options' => array('label' => 'form.new_password_confirmation'),
          'invalid_message' => 'fos_user.password.mismatch',
      ));
    }

    public function configureOptions(OptionsResolver $resolver) {
        $defaults['csrf_protection'] = false;
        $defaults['allow_extra_fields'] = true;
        $defaults['cascade_validation'] = true;
        $defaults['data_class'] = "AppBundle\Entity\User";

        $defaults['validationGroups'] = array('editPassword');
        $resolver->setDefaults($defaults);
    }

    // BC for SF < 2.7
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $this->configureOptions($resolver);
    }

    // BC for SF < 3.0
    public function getName() {
        return $this->getBlockPrefix();
    }

    public function getBlockPrefix() {
        return 'api_user_change_password';
    }

}
