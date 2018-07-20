<?php

namespace App\FormType;

use App\Entity\DirectoryObject;
use App\Entity\DirectoryRelation;
use App\Entity\Enum\RelationEnum;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DirectoryRelationInputType extends AbstractType {
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder->add("type", ChoiceType::class, [
				"choices" => RelationEnum::getEnumerators(),
				"choice_label" => "name"
			])
			->add("target", EntityType::class, [
				"class" => DirectoryObject::class,
				"choice_label" => "name"
			]);
	}

	public function configureOptions(OptionsResolver $resolver) {
		$resolver->setDefaults([
			"data_class" => DirectoryRelation::class
		]);
	}

	public function getBlockPrefix() {
		return "directory_relation";
	}
}