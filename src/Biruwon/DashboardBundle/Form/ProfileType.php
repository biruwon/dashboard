<?php

namespace Biruwon\DashboardBundle\Form;

use Symfony\Component\Form\AbstractType,
	Symfony\Component\Form\FormBuilderInterface,
	Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProfileType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('image', new DocumentType())
			->add('update', 'submit', array(
				'attr' => array(
					'class' => 'btn btn-primary'
					)
				)
			);
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver){

		$resolver->setDefaults(array(
			'data_class' => 'Biruwon\DashboardBundle\Entity\Profile'
		));
	}

	public function getName()
	{
		return 'profile';
	}
}