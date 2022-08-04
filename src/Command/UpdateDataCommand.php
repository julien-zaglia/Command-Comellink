<?php

namespace App\Command;


use DateTime;
use App\Entity\Update;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;


#[AsCommand(
    name: 'app:UpdateData',
    description: 'Voir la moyenne des chiffres/nombres, le plus grand et le plus petit !')]

class UpdateDataCommand extends Command
{
    private EntityManagerInterface $entityManager; 

    private string $dataDirectory; 

    private SymfonyStyle $io;

    private $data;


    public function __construct(EntityManagerInterface $entityManager, string $dataDirectory)
    {
        $this->dataDirectory = $dataDirectory;
        $this->entityManager = $entityManager;
        parent::__construct();
    }

    protected function configure(): void
    {
       
    }

    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        $this->io = new SymfonyStyle($input, $output);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->updateData();
        return Command::SUCCESS;
    }

    private function getDataFromFile()
    {
        $file= $this->dataDirectory . '/test-comellink.csv';

        $fileExtension = pathinfo($file, PATHINFO_EXTENSION);  // pour récupérer l'extension csv yaml xml....

        $normalizers = [new ObjectNormalizer()];

        $encoders = [
            new CsvEncoder()
        ];

        $serializer = new Serializer($normalizers, $encoders);

        
        /** @var string $fileString */
        $fileString = file_get_contents($file);
        $data = $serializer->decode($fileString, $fileExtension);
        $this->setData($data);
    }

    public function setData(array $data)
    {
        $this->data = $data;
        return $this;
    }

    public function getData()
    {
        return $this->data;
    }

    private function calculMoyenne($data)
    {
        $somme = array_sum(array_column($data, "price"));
        $rows = count($data);
        $moyenne = $somme / $rows;
        return floatval(number_format($moyenne,2));
    }

    private function calculMax($data)
    {
        $max = max($data);
        $max = floatval($max['price']);
        return $max;
    }

    private function calculMin($data)
    {
        $min = min($data);
        $min = floatval($min['price']);
        return $min;
    }

    private function updateData(): void
    {
        $this->io->section('Roulement de tambour !');
        $this->getDataFromFile();
        $this->calculMoyenne($this->data);


        $updateData= new Update;
        $updateData->setMoyenne($this->calculMoyenne($this->data));
        $updateData->setMax($this->calculMax($this->data));
        $updateData->setMin($this->calculMin($this->data));
        $updateData->setDatime(new \DateTime());

        $this->entityManager->persist($updateData);
        $this->entityManager->flush();

        $this->io->text('La moyenne est de :');
        $this->io->text($updateData->getMoyenne());
        $this->io->text('La maximum est de :');
        $this->io->text($updateData->getMax());
        $this->io->text('Le minimum est de :');
        $this->io->text($updateData->getMin());
    }
}

