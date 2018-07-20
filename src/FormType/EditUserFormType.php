<?php
namespace App\FormType;

use App\Entity\DirectoryRelation;
use App\Entity\User;
use App\Entity\DirectoryRole;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class EditUserFormType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options) {
        /* @var User $user */
        $user = $builder->getData();
		$prototype = new DirectoryRelation();
		$prototype->setOwner($user);
        $builder->add("username", TextType::class)
            ->add("fullName", TextType::class)
            ->add("plainPassword", RepeatedType::class, array(
                "type" => PasswordType::class,
                "required" => false,
                "invalid_message" => "Repeat password did not match.",
                "first_options"  => array(
                    "required" => false,
                    "label" => "Password"
                ),
                "second_options" => array(
                    "required" => false,
                    "label" => "Repeat Password"
                ),
            ))
            ->add('email', EmailType::class)
            ->add("rolesCollection", EntityType::class, array(
                "class" => DirectoryRole::class,
                "multiple" => true,
                "expanded" => true,
                "choice_label" => function(DirectoryRole $role) {
                    switch ($role->getRole()) {
                        case "ROLE_ADMIN":
                            return "Administrator";
                        default:
                            return $role->getRole();
                    }
                }))
			->add("relations", CompositeCollectionType::class, [
				"entry_type" => DirectoryRelationInputType::class,
				"allow_add" => true,
				"allow_delete" => true,
				"prototype" => true,
				"prototype_data" => $prototype,
				"delete_empty" => true,
				"entry_options" => [
					"label" => false
				]
			])
            ->add("submit", SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}