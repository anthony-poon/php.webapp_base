<?php

namespace App\Command;

use App\Entity\SecurityGroup;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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
		$output->writeln("Creating root users");
		$root = new User();
		$root->setUsername("root");
		$root->setFullName("root");
		$password = md5(random_bytes(10));
		$passwordHash = $this->passwordEncoder->encodePassword($root, $password);
		$root->setPassword($passwordHash);

    	$output->writeln("Creating User Group");
        $adminGroup = new SecurityGroup();
		$adminGroup->setName("Administrator Group");
		$adminGroup->setSiteToken("ROLE_ADMIN");
        $adminGroup->getChildren()->add($root);

        $this->entityManager->persist($adminGroup);
        $this->entityManager->persist($root);
        $output->writeln("Username: root");
		$output->writeln("Password: ".$password);
		$this->entityManager->flush();
    }

}