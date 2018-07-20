<?php
/**
 * Created by PhpStorm.
 * User: ypoon
 * Date: 19/7/2018
 * Time: 5:35 PM
 */

namespace App\FormType;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class CompositeCollectionType extends AbstractType {

	public function getParent() {
		return CollectionType::class;
	}

	public function getBlockPrefix() {
		return "composite_collection";
	}

}