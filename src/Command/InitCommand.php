<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\UserRole;

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
        $repo = $this->entityManager->getRepository(User::class);
        $root = $repo->findOneBy(["username" => "root"]);
        if ($root) {
            $output->writeln("Root user exist already. Terminating.");
        } else {
            $output->writeln("Creating root users");
            $root = new User();
            $root->setUsername("root");
            $root->setFullName("root");
            $password = md5(random_bytes(10));
            $passwordHash = $this->passwordEncoder->encodePassword($root, $password);
            $root->setPassword($passwordHash);
            $this->entityManager->persist($root);

            $role = new UserRole("ROLE_ADMIN");
            $role->setUser($root);
            $this->entityManager->persist($role);
            $this->entityManager->flush();
            $output->writeln("Root user created.");
            $output->writeln("Username: root");
            $output->writeln("Password: ".$password);
        }
    }
}