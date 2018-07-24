<?php
/**
 * Created by PhpStorm.
 * User: ypoon
 * Date: 23/7/2018
 * Time: 12:13 PM
 */

namespace App\FormType\Form\UserGroups;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChooseUserGroupsTypeForm extends AbstractType {
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder->add("groupType", ChoiceType::class, [
				"choices" => [
					"Directory Group" => "directory_group",
					"Security Group" => "security_group"
				]
			])->add("submit", SubmitType::class);
	}

	public function configureOptions(OptionsResolver $resolver) {

	}
}