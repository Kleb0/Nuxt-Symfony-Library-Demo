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
    name: 'app:assign-admin-status',
    description: 'Assigne le status Admin à l\'utilisateur avec le pseudo "admin"'
)]
class AssignAdminStatusCommand extends Command
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

        // Trouver l'utilisateur avec le pseudo "admin"
        $userRepository = $this->entityManager->getRepository(User::class);
        $adminUser = $userRepository->findOneBy(['pseudo' => 'admin']);

        if (!$adminUser) {
            $io->error('Aucun utilisateur trouvé avec le pseudo "admin"');
            return Command::FAILURE;
        }

        // Trouver le status Admin (statusState = 2)
        $statusRepository = $this->entityManager->getRepository(Status::class);
        $adminStatus = $statusRepository->findOneBy(['statusState' => 2]);

        if (!$adminStatus) {
            $io->error('Status Admin (statusState = 2) non trouvé');
            return Command::FAILURE;
        }

        // Vérifier si l'utilisateur a déjà le status Admin
        $hasAdminStatus = false;
        foreach ($adminUser->getStatuses() as $status) {
            if ($status->getStatusState() === 2) {
                $hasAdminStatus = true;
                break;
            }
        }

        if ($hasAdminStatus) {
            $io->success('L\'utilisateur admin a déjà le status Admin');
            return Command::SUCCESS;
        }

        // Assigner le status Admin
        $adminUser->addStatus($adminStatus);

        try {
            $this->entityManager->flush();
            $io->success('Status Admin assigné avec succès à l\'utilisateur admin');
            
            // Afficher les status de l'utilisateur
            $io->section('Status de l\'utilisateur admin :');
            foreach ($adminUser->getStatuses() as $status) {
                $io->writeln(sprintf('- %s (ID: %d, State: %d)', 
                    $status->getStatusName(), 
                    $status->getStatusId(), 
                    $status->getStatusState()
                ));
            }
            
        } catch (\Exception $e) {
            $io->error('Erreur lors de l\'assignation du status : ' . $e->getMessage());
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}