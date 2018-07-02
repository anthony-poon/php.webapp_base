<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserRole;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends Controller {
    /**
     * @Route("/admin/users", name="admin_list_user")
     */
    public function listUser() {
        $user = new User();
        $addForm = $this->createFormBuilder($user)
            ->add("username", TextType::class)
            ->add("fullname", TextType::class)
            ->add("plainPassword", RepeatedType::class, array(
                "type" => PasswordType::class,
                "invalid_message" => "Repeat password did not match.",
                "first_options"  => array("label" => "Password"),
                "second_options" => array("label" => "Repeat Password"),
            ))
            ->add('userRoles', EntityType::class, array(
                'class' => UserRole::class,
                'choice_label' => 'description',
                'multiple' => true,
                'expanded' => true,
            ))
            ->getForm();
        return $this->render('admin/list_user.html.twig', [
            "addForm" => $addForm->createView()
        ]);
    }
    /**
     * @Route("/api/admin/user", name="api_admin_list_user")
     * @Method({"GET"})
     */
    public function listUserAPI() {
        $repo = $this->getDoctrine()->getRepository(User::class);
        $json = array_map(function($user){
            /* @var \App\Entity\User $user */
            return [
                "id" => $user->getId(),
                "full_name" => $user->getFullName(),
                "username" => $user->getUsername(),
                "email" => $user->getEmail(),
            ];
        } , $repo->findAll());
        return new JsonResponse($json);
    }

    /**
     * @Route("/api/admin/user/{id}", name="api_admin_view_user", requirements={"id" = "\d+"})
     * @Method({"GET"})
     */
    public function viewUserAPI(int $id) {
        $repo = $this->getDoctrine()->getRepository(User::class);
        $user = $repo->find($id);
        $serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);
        $jsonStr = $serializer->serialize($user, 'json');
        return new Response(
            $jsonStr,
            200,
            ["Content-Type" => "application/json"]
        );
    }
}
