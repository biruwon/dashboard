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
			->add('email', 'email', array(
				'required' => true,
				'attr' => array(
					'class' => 'form-control',
					'placeholder' => 'john@company.com'
					)
				)
			)
			->add('username', 'text', array(
				'required' => true,
				'attr' => array(
					'class' => 'form-control',
					'placeholder' => 'UsEr745'
					)
				))
			->add('password', 'repeated', array(
				'first_options'  => array(
					'attr' => array(
						'class' => 'form-control',
						'placeholder' => 'mypass'
						)
					),
				'second_options' => array(
					'attr' => array(
						'class' => 'form-control',
						'placeholder' => 'mypass'
						)
					),
				'invalid_message' => 'The password fields must match.',
				'type' => 'password',
				'required' => true
				)
			)
			->add('register', 'submit', array(
				'attr' => array(
					'class' => 'btn btn-primary'
					)
				)
			)
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