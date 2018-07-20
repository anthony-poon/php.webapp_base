<?php
namespace App\FormType;

use App\Entity\Enum\UserRoleEnum;
use App\Entity\User;
use App\Entity\DirectoryRole;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class CreateUserFormType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add("username", TextType::class)
            ->add("fullName", TextType::class)
            ->add("plainPassword", RepeatedType::class, array(
                "type" => PasswordType::class,
                "invalid_message" => "Repeat password did not match.",
                "first_options"  => array("label" => "Password"),
                "second_options" => array("label" => "Repeat Password"),
            ))
            ->add('email', EmailType::class)
			->add("rolesCollection", EntityType::class, array(
				"class" => DirectoryRole::class,
				"multiple" => true,
				"expanded" => true,
				"choice_label" => function($role) {
					switch ($role) {
						case "ROLE_ADMIN":
							return "Administrator";
						default:
							return $role;
					}
				}))
			->add("submit", SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'data_class' => User::class,
            'validation_groups' => 'registration'
        ]);
    }
}