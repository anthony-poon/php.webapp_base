<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserRole;
use App\FormType\CreateUserFormType;
use App\FormType\EditUserFormType;
use App\Service\BaseTemplateHelper;
use Doctrine\ORM\Query\Expr\Base;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminController extends Controller {
    public function __construct(BaseTemplateHelper $helper) {
        $helper->setTitle("Administration");
    }
    /**
     * @Route("/admin", name="admin_list_user")
     */
    public function defaultAction() {
        return $this->redirectToRoute("admin_list_user");
    }

    /**
     * @Route("/admin/users", name="admin_list_user")
     */
    public function listUser(BaseTemplateHelper $helper, RouterInterface $router) {
        $repo = $this->getDoctrine()->getRepository(User::class);
        $userList = $repo->findAll();
        $helper->setJsParam([
            "addPath" => $this->generateUrl("admin_create_user"),
            "editPath" => $router->getRouteCollection()->get("admin_edit_user")->getPath(),
            "deletePath" => $router->getRouteCollection()->get("api_admin_delete_user")->getPath()
        ]);
        return $this->render('admin/list_user.html.twig', [
            "userList" => $userList
        ]);
    }

    /**
     * @Route("/admin/users/create", name="admin_create_user")
     */
    public function createUser(Request $request, UserPasswordEncoderInterface $encoder) {
        $user = new User();
        $form = $this->createForm(CreateUserFormType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /* @var \App\Entity\User $user */
            $user = $form->getData();
            $pw = $encoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($pw);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute("admin_list_user");
        }
        return $this->render("admin/user.html.twig", [
            "title" => "Create User",
            "form" => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/users/{id}", name="admin_edit_user", requirements={"id"="\d+"})
     */
    public function editUser(Request $request, UserPasswordEncoderInterface $encoder, int $id) {
        $repo = $this->getDoctrine()->getRepository(User::class);
        $user = $repo->find($id);
        if (!$user) {
            throw new \Exception("Invalid user id.");
        }
        $form = $this->createForm(EditUserFormType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /* @var \App\Entity\User $user */
            $user = $form->getData();
            $pw = $user->getPlainPassword();
            if ($pw) {
                $pw = $encoder->encodePassword($user, $pw);
                $user->setPassword($pw);
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            foreach ($user->getRoles() as $role) {
                $role->setUser($user);
                $em->persist($role);
            }
            $em->flush();
            return $this->redirectToRoute("admin_list_user");
        }
        return $this->render("admin/user.html.twig", [
            "title" => "Edit User",
            "form" => $form->createView()
        ]);
    }

    /**
     * @Route("/api/admin/users/delete",
     *     name="api_admin_delete_user",
     *     methods={"POST", "DELETE"})
     */
    public function deleteUser(Request $request) {
        $idArr = json_decode($request->getContent(), true);
        $userArr = $this->getDoctrine()->getRepository(User::class)->findBy([
            "id" => $idArr
        ]);
        $em = $this->getDoctrine()->getManager();
        foreach ($userArr as $user) {
            $em->remove($user);
            foreach ($user->getRoles() as $role) {
                $em->remove($role);
            }
        }
        $em->flush();
        return new JsonResponse([
            "status" => "success"
        ]);
    }
}
