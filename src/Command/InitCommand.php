<?php

namespace App\Command;

use App\Entity\Base\Directory\DirectoryGroup;
use App\Entity\Base\Directory\SitePermission;
use App\Entity\Base\User;
use App\Entity\Base\UserGroup;
use App\Entity\Core\Department;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class InitCommand extends Command {
    private $em;
    private $passwordEncoder;
    /**
     * @var OutputInterface
     */
    private $output;
    private $folder;

    public function __construct(EntityManagerInterface $em, ParameterBagInterface $bag, UserPasswordEncoderInterface $passwordEncoder, $name = null) {
        $this->em = $em;
        $this->folder = realpath($bag->get("assets_path"));
        if (!$this->folder) {
            throw new \Exception("Invalid var folder.");
        }
        $this->passwordEncoder = $passwordEncoder;
        parent::__construct($name);
    }

    protected function configure() {
        $this->setName("app:init")
            ->setDescription("Create root user and role");
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output) {
        $this->output = $output;
        $output->writeln("Creating Everyone Group");
        $allGroup = $this->initGroup("Everyone");

        $output->writeln("Creating Admin Group");
        $adminGroup = $this->initGroup("All Admins");

        $output->writeln("Creating User Group");
        $userGroup = $this->initGroup("All Users");

        $adminGroup->joinGroups($allGroup);
        $userGroup->joinGroups($allGroup);

        $output->writeln("Creating Admin Permission");
        $adminPermission = $this->initSitePermission("ROLE_ADMIN");
        $switchUserPermission = $this->initSitePermission("ROLE_ALLOWED_TO_SWITCH");
        $userPermission = $this->initSitePermission("ROLE_USER");

        $adminPermission->setBearer($adminGroup);
        $switchUserPermission->setBearer($adminGroup);
        $userPermission->setBearer($userGroup);

        $output->writeln("Creating root users");
        $root = $this->initUser("root", "password");
        $root->joinGroups($adminGroup);

        $this->em->persist($root);
        $this->em->persist($allGroup);
        $this->em->persist($adminGroup);
        $this->em->persist($userGroup);
        $this->em->persist($adminPermission);
        $this->em->persist($switchUserPermission);
        $this->em->persist($userPermission);
        $this->em->flush();


        $output->writeln("Username: root");
        $output->writeln("Password: ".$root->getPlainPassword());
        $output->writeln("Role:");
        foreach ($root->getRoles() as $role) {
            $output->writeln($role);
        }
    }

    private function initSitePermission(string $roleName) {
        $repo = $this->em->getRepository(SitePermission::class);
        $token = $repo->findOneBy([
            "role" => $roleName
        ]);
        if (!$token) {
            $token = new SitePermission();
            $token->setRole($roleName);
        }
        return $token;
    }

    private function initGroup(string $groupName): DirectoryGroup {
        $repo = $this->em->getRepository(UserGroup::class);
        $group = $repo->findOneBy([
            "groupName" => $groupName
        ]);
        if (!$group) {
            $group = new UserGroup();
            $group->setGroupName($groupName);
        }
        return $group;
    }

    private function initUser(string $username, string $password = "password"): User {
        $userRepo = $this->em->getRepository(User::class);
        $user = $userRepo->findOneBy([
            "username" => $username
        ]);
        if (!$user) {
            $user = new User();
            $user->setUsername($username);
            $user->setFullName($username);
        }
        $user->setPlainPassword($password);
        $user->setPassword($this->passwordEncoder->encodePassword($user, $password));
        return $user;
    }
}