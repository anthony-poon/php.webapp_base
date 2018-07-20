<?php

namespace App\Command;

use App\Entity\Enum\UserRoleEnum;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\DirectoryRole;

class InitCommand extends Command {
    private $entityManager;
    private $passwordEncoder;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder, $name = null) {
        $this->entityManager = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
        parent::__construct($name);
    }

    protected function configure() {
        $this->setName("app:init")
            ->setDescription("Create root user and role");
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
		$output->writeln("Creating User Group");
        $roles = $this->initUserRole();
		$output->writeln("Creating root users");
		$root = $this->initRootUser();

		$output->writeln("Root user created.");
		$output->writeln("Username: root");
		$output->writeln("Password: ".$root->getPlainPassword());
    }

    private function initUserRole(): array {
    	$roles = [];
		$default = UserRoleEnum::getValues();
		foreach ($default as $roleName) {
			$role = new DirectoryRole($roleName);
			$this->entityManager->persist($role);
			$roles[] = $role;
		}
		$this->entityManager->flush();
		return $roles;
	}

    private function initRootUser(): User {
		$root = new User();
		$root->setUsername("root");
		$root->setFullName("root");
		$password = md5(random_bytes(10));
		$root->setPlainPassword($password);
		$passwordHash = $this->passwordEncoder->encodePassword($root, $password);
		$root->setPassword($passwordHash);
		/* @var \App\Entity\DirectoryRole $adminRole */
		$adminRole = $this->entityManager->getRepository(DirectoryRole::class)->findOneBy([
			"roleName" => UserRoleEnum::ADMIN
		]);
		$root->getRolesCollection()->add($adminRole);
		$this->entityManager->persist($root);
		$this->entityManager->flush();
		return $root;
	}
}