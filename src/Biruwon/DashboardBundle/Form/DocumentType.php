<?php

namespace Biruwon\DashboardBundle\Form;

use Symfony\Component\Form\AbstractType,
	Symfony\Component\Form\FormBuilderInterface,
	Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DocumentType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('file', 'file', array(
				'attr' => array(
					'class' => 'form-control'
					),
				'label' => 'Add new...'
				)
			)
		;
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver){

		$resolver->setDefaults(array(
			'data_class' => 'Biruwon\DashboardBundle\Entity\Document'
		));
	}

	public function getName()
	{
		return 'document';
	}
}