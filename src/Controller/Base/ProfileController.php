<?php

namespace App\Controller\Base;

use App\Entity\Base\SecurityGroup;
use App\Entity\Base\User;
use App\FormType\Form\Users\EditSelfUsersForm;
use App\FormType\Form\Users\SelfRegisterForm;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ProfileController extends Controller {
    /**
     * @Route("/profile", name="profile_edit")
     */
    public function edit(Request $request, UserPasswordEncoderInterface $encoder) {
        $self = $this->getUser();
        $form = $this->createForm(EditSelfUsersForm::class, $self);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $self = $form->getData();
            $password = $self->getPlainPassword();
            if ($password) {
                $password = $encoder->encodePassword($self, $self->getPlainPassword());
                $self->setPassword($password);
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($self);
            $em->flush();
        }
        return $this->render('render/simple_form.html.twig', [
            "title" => "Profile Management",
            "form" => $form->createView()
        ]);
    }

    /**
     * @Route("/profile/register", name="profile_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $encoder) {
        $user = new User();
        $form = $this->createForm(SelfRegisterForm::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /* @var \App\Entity\Base\User $user */
            /* @var \App\Entity\Base\SecurityGroup $userGrp */
            $repo = $this->getDoctrine()->getRepository(SecurityGroup::class);
            $userGrp = $repo->findOneBy([
                "siteToken" => "ROLE_USER"
            ]);
            $user = $form->getData();
            $pw = $encoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($pw);
            $userGrp->addChild($user);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->persist($userGrp);
            $em->flush();
            return $this->redirectToRoute("security_login");
        }
        return $this->render('render/simple_form.html.twig', [
            "title" => "Register Account",
            "form" => $form->createView()
        ]);
    }
}
