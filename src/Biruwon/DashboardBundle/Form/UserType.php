<?php

namespace Biruwon\DashboardBundle\Form;

use Symfony\Component\Form\AbstractType,
	Symfony\Component\Form\FormBuilderInterface,
	Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('email', 'email', array('required' => true))
			->add('username')
			->add('password', 'repeated', array(
				'first_options'  => array('label' => 'Password'),
				'second_options' => array('label' => 'Repeat Password'),
				'invalid_message' => 'The password fields must match.',
				'type' => 'password',
				'required' => true
				)
			)
			->add('create', 'submit')
		;
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver){

		$resolver->setDefaults(array(
			'data_class' => 'Biruwon\DashboardBundle\Entity\User'
		));
	}

	public function getName()
	{
		return 'user';
	}
}