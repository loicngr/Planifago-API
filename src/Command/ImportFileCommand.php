<?php

namespace App\Command;

use App\Entity\City;
use App\Entity\Department;
use App\Repository\CityRepository;
use App\Repository\DepartmentRepository;
use Doctrine\ORM\EntityManagerInterface;
use League\Csv\CharsetConverter;
use League\Csv\TabularDataReader;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use League\Csv\Reader;
use League\Csv\Statement;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ImportFileCommand extends Command
{
    protected static $defaultName = 'app:import:file';

    /** @var EntityManagerInterface */
    private $em ;

    /** @var ParameterBagInterface */
    private $params ;

    /** @var DepartmentRepository */
    private $departmentRepository ;

    /** @var CityRepository */
    private $cityRepository ;

    public function __construct(
        EntityManagerInterface $em,
        ParameterBagInterface $params,
        DepartmentRepository $departmentRepository,
        CityRepository $cityRepository
    ) {
        $this->em = $em;
        $this->params = $params;
        $this->departmentRepository = $departmentRepository;
        $this->cityRepository = $cityRepository;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Import CSV to Entity')
            ->addArgument('filename', InputArgument::REQUIRED, 'Filename')
            ->addArgument('entity', InputArgument::REQUIRED, 'Entity name')
            ->addOption('offset', null, InputOption::VALUE_NONE, 'Offset')
            ->addOption('limit', null, InputOption::VALUE_NONE, 'Limit')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $filename = $input->getArgument('filename');
        $entity = $input->getArgument('entity');
        $offset = 0 ;
        $limit = 10000 ;

        if($input->getOption('offset')): $offset = (int)$input->getOption('offset'); endif;
        if($input->getOption('limit')): $limit = (int)$input->getOption('limit'); endif;


        if ($entity && $filename) {
            $csv = Reader::createFromPath($this->params->get('import_directory') . '/' . $filename, 'r');
            $csv->setDelimiter(';');
            $csv->setHeaderOffset(0);
            $input_bom = $csv->getInputBOM();

            if ($input_bom === Reader::BOM_UTF16_LE || $input_bom === Reader::BOM_UTF16_BE) {
                CharsetConverter::addTo($csv, 'utf-16', 'utf-8');
            }

            $stmt = (new Statement())->offset($offset)->limit($limit);
            $records = $stmt->process($csv);
            $return = 1 ;
            switch($entity):
                case 'App\Entity\Department':
                case 'App\Entity\City':
                    $this->importDepartmentCity($records);
                break;
            endswitch;

            $io->success("The file {$filename} has been imported into the table with entity : {$entity}");
            if(!$return): return Command::SUCCESS; endif;
        }
        return Command::FAILURE;
    }

    private function importDepartmentCity(TabularDataReader $records): int
    {
        foreach($records as $key => $record):
            // Département
            $departement_name = $record['Departement'];
            $department = $this->departmentRepository->findOneBy(['name' => $departement_name]);
            if(!$department):
                $department = new Department();
                $department->setName($departement_name);
                $this->em->persist($department);
            endif;

            // City
            $city_name = $record['Commune'];
            $postcode = $record['Codepos'];
            $city = $this->cityRepository->findOneBy(['name' => $city_name, 'postalcode' => $postcode]);
            if(!$city):
                $city = new City();
                $city->setName($city_name);
                $city->setPostalcode($postcode);
                $city->setDepartment($department);
                $this->em->persist($city);
            endif;
            $this->em->flush();
        endforeach;
        return 0 ;
    }
}
