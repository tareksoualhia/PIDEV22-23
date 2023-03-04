<?php
namespace App\Command;

use App\Entity\Abonnement;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DecreaseAbonnementsCommand extends Command
{
    protected static $defaultName = 'app:decrease-duration';

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Decreases the duration of active abonnements every day.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $abonnements = $this->entityManager->getRepository(Abonnement::class)->findAll();

        foreach ($abonnements as $abonnement) {
            $duree = $abonnement->getDuree();
            if ($duree > 0) {
                $abonnement->setDuree($duree - 1);
            } else {
                $this->entityManager->remove($abonnement);
            }
        }

        $this->entityManager->flush();

        $output->writeln('Abonnement durations updated successfully.');

        return Command::SUCCESS;
    }
}
