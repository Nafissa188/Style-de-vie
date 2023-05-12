<?php

namespace ApiBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class GenericType extends AbstractType {

    protected $wsConfig;
    protected $fields;
    protected $validationGroups;

    public function __construct($wsConfig, $validationGroups = null) {
        $this->wsConfig = $wsConfig;
        $this->validationGroups = $validationGroups;
    }

    public function getFields() {
        return $this->wsConfig['fields'];
    }

    function getValidationGroups() {
        return $this->validationGroups;
    }

    function setValidationGroups($validationGroups) {
        $this->validationGroups = $validationGroups;
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $customOption = array();
        foreach ($this->getFields() as $field => $config) {
            if (isset($config['validations'])) {
                foreach ($config['validations'] as $validation => $validationAttrs) {
                    $validationConstraint = '\Symfony\Component\Validator\Constraints\\' . $validation;
                    $customOption['constraints'][] = new $validationConstraint($validationAttrs);
//                    $config['constraints'][] = new $validationConstraint($validationAttrs);
                }
            }
            $builder->add($field, isset($config['type']) ? $config['type'] : null, isset($customOption) ? $customOption : array());
        }
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $defaults['csrf_protection'] = false;
        $defaults['allow_extra_fields'] = true;
        $defaults['cascade_validation'] = true;
//        $defaults['error_bubbling'] = true;

        if ($this->getValidationGroups()) {
            $defaults['validationGroups'] = array($this->getValidationGroups());
        }
        $resolver->setDefaults($defaults);
    }

    public function getName() {
        return 'generic_type_validator';
    }

}
