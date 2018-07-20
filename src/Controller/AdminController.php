<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\DirectoryRelation;
use App\Entity\DirectoryGroup;
use App\Entity\DirectoryRole;
use App\FormType\CreateUserFormType;
use App\FormType\EditUserFormType;
use App\FormType\UserGroupFormType;
use App\Service\BaseTemplateHelper;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Expr\Base;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminController extends Controller {
    public function __construct(BaseTemplateHelper $helper, RouterInterface $router) {
        $helper->setTitle("Administration");
        $helper->addSideMenuItem([
        		"text" => "User Management",
				"url" => $router->generate("admin_list_user")
			])->addSideMenuItem([
				"text" => "Group Management",
				"url" => $router->generate("admin_list_user_group")
			]);
    }

    /**
     * @Route("/admin", name="admin_list_user")
     */
    public function index() {
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
        return $this->render("admin/create_edit_form.html.twig", [
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
			$em->persist($user->getRelations());
            $em->flush();
            return $this->redirectToRoute("admin_list_user");
        }
        return $this->render("admin/create_edit_form.html.twig", [
            "title" => "Edit User",
            "form" => $form->createView()
        ]);
    }

	/**
	 * @Route("/admin/user-groups", name="admin_list_user_group")
	 */
    public function listUserGroup(BaseTemplateHelper $helper, RouterInterface $router) {
        $grpRepo = $this->getDoctrine()->getRepository(DirectoryGroup::class);
        $grpList = $grpRepo->findAll();
        $helper->setJsParam([
        	"addPath" => $this->generateUrl("admin_create_user_group"),
			"editPath" => $router->getRouteCollection()->get("admin_edit_user_group")->getPath(),
			"deletePath" => $router->getRouteCollection()->get("api_admin_delete_user_group")->getPath()
		]);
        return $this->render("admin/list_user_group.html.twig", [
        	"grpList" => $grpList
		]);
    }

	/**
	 * @Route("/admin/user-groups/create", name="admin_create_user_group")
	 */
	public function createUserGroup(BaseTemplateHelper $helper, Request $request) {
		$form = $this->createForm(UserGroupFormType::class);
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			$data = $form->getData();
			$em = $this->getDoctrine()->getManager();
			$em->persist($data);
			$em->flush();
			$this->redirectToRoute("admin_list_user_group");
		}
		return $this->render("admin/create_edit_form.html.twig", [
			"title" => "Create User Group",
			"form" => $form->createView()
		]);
	}

	/**
	 * @Route("/admin/user-groups/{id}", name="admin_edit_user_group", requirements={"id"="\d+"})
	 */
	public function editUserGroup(BaseTemplateHelper $helper, Request $request, int $id) {
		$grpRepo = $this->getDoctrine()->getRepository(DirectoryGroup::class);
		$grp = $grpRepo->find($id);
		$form = $this->createForm(UserGroupFormType::class, $grp);
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			$data = $form->getData();
			$em = $this->getDoctrine()->getManager();
			$em->persist($data);
			$em->flush();
			$this->redirectToRoute("admin_list_user_group");
		}
		return $this->render("admin/create_edit_form.html.twig", [
			"title" => "Edit User Group",
			"form" => $form->createView()
		]);
	}

	/**
	 * @Route("/admin/user-relation", name="admin_create_user_relation", requirements={"id"="\d+"})
	 */
	public function listUserRelation() {

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
        }
        $em->flush();
        return new JsonResponse([
            "status" => "success"
        ]);
    }

	/**
	 * @Route("/api/admin/user-groups/delete",
	 *     name="api_admin_delete_user_group",
	 *     methods={"POST", "DELETE"})
	 */
	public function deleteUserGroup(Request $request) {
		$idArr = json_decode($request->getContent(), true);
		$grpList = $this->getDoctrine()->getRepository(DirectoryGroup::class)->findBy([
			"id" => $idArr
		]);
		$em = $this->getDoctrine()->getManager();
		foreach ($grpList as $group) {
			$em->remove($group);
		}
		$em->flush();
		return new JsonResponse([
			"status" => "success"
		]);
	}
}
