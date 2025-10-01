<?php

namespace App\Command;

use App\Entity\User;
use App\Entity\Status;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:test-admin-creation',
    description: 'Test de création d\'un utilisateur admin via l\'event listener'
)]
class TestAdminCreationCommand extends Command
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $newAdmin = new User();
        $newAdmin->setNom('AdminTest');
        $newAdmin->setPrenom('Test');
        $newAdmin->setPseudo('admin'); 
        $newAdmin->setMail('admin-test@example.com');
        $newAdmin->setMotDePasse('admin'); 
        $newAdmin->setAdresse('123 Admin Street');
        $newAdmin->setTelephone('0123456789');

        try {
            $this->entityManager->persist($newAdmin);
            $this->entityManager->flush();

            $io->success('Nouvel utilisateur admin créé avec succès !');
            
            $io->section('Status assignés à l\'utilisateur :');
            
            $this->entityManager->refresh($newAdmin);
            
            foreach ($newAdmin->getStatuses() as $status) {
                $io->writeln(sprintf('- %s (ID: %d, State: %d)', 
                    $status->getStatusName(), 
                    $status->getStatusId(), 
                    $status->getStatusState()
                ));
            }

            if ($newAdmin->getStatuses()->isEmpty()) {
                $io->warning('Aucun status assigné ! L\'event listener n\'a pas fonctionné.');
            }
            
        } catch (\Exception $e) {
            $io->error('Erreur lors de la création de l\'utilisateur : ' . $e->getMessage());
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}