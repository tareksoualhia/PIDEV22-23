<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:decrease-duration',
    description: 'Decreases the duration of active abonnements every day.',
)]
class DecreaseAbonnementsCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $entityManager = $this->getContainer()->get('doctrine')->getManager();
        $abonnements = $entityManager->getRepository(Abonnement::class)->findAll();
    
        foreach ($abonnements as $abonnement) {
            $duree = $abonnement->getDuree();
            if ($duree > 0) {
                $abonnement->setDuree($duree - 1);
            } else {
                $entityManager->remove($abonnement);
            }
        }
    
        $entityManager->flush();
    
        $output->writeln('Abonnement durations updated successfully.');
    }
}
