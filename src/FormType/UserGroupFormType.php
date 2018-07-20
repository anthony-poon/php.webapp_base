<?php
/**
 * Created by PhpStorm.
 * User: ypoon
 * Date: 18/7/2018
 * Time: 12:03 PM
 */

namespace App\FormType;


use App\Entity\DirectoryGroup;
use App\Entity\DirectoryObject;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class UserGroupFormType extends AbstractType {
	public function buildForm(FormBuilderInterface $builder, array $options) {
		/* @var \App\Entity\DirectoryObject $dataObj */
		$dataObj = $builder->getData();
		$builder->add("groupName", TextType::class)
			->add("members", EntityType::class, [
				// TODO: Better ordering and display
				"class" => DirectoryObject::class,
				"choice_label" => "name",
				"expanded" => true,
				"multiple" => true,
				"query_builder" => function(EntityRepository $repo) use ($dataObj){
					if ($dataObj) {
						return $repo->createQueryBuilder("do")
							->andWhere('do.id != :id')
							->setParameter("id", $dataObj->getId());
					} else {
						return $repo->createQueryBuilder("do");
					}
				}
			])
			->add("submit", SubmitType::class);
	}

	public function configureOptions(OptionsResolver $resolver) {
		$resolver->setDefaults([
			"data_class" => DirectoryGroup::class
		]);
	}
}