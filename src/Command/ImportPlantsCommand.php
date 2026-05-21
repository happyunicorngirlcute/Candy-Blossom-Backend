<?php

namespace App\Command;

use App\Entity\Plant;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Contracts\HttpClient\HttpClientInterface;

use App\Service\PlantMapperService;

#[AsCommand(
    name: 'plantes',
    description: 'Importe 50 plantes depuis Perenual API',
)]
class ImportPlantsCommand extends Command
{
    private string $pageFile;

    public function __construct(
        private HttpClientInterface $httpClient,
        private EntityManagerInterface $em,
        private PlantMapperService $plantMapper,
        private string $apiKey
    ) {
        parent::__construct();
        $this->pageFile = __DIR__ . '/../../.perenual_page';
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Import des plantes depuis Perenual API');

        $page = file_exists($this->pageFile) ? (int)file_get_contents($this->pageFile) : 1;
        $io->info('Reprise à la page : ' . $page);

        // Étape 1 — récupère la liste avec les IDs
        $response = $this->httpClient->request('GET', 'https://perenual.com/api/species-list', [
            'query' => [
                'key' => $this->apiKey,
                'per_page' => 50,
                'page' => $page
            ]
        ]);

        $data = $response->toArray();

        if (empty($data['data'])) {
            $io->warning('Plus de plantes disponibles sur l\'API, tu as tout importé!');
            return Command::SUCCESS;
        }

        $count = 0;

        foreach ($data['data'] as $plantData) {

            $existing = $this->em->getRepository(Plant::class)->findOneBy([
                'common_name' => $plantData['common_name']
            ]);

            if ($existing) {
                $io->warning('Doublon ignoré : ' . $plantData['common_name']);
                continue;
            }

            // Étape 2 — récupère les détails complets avec l'ID
            $detailResponse = $this->httpClient->request('GET', 'https://perenual.com/api/v2/species/details/' . $plantData['id'], [
                'query' => ['key' => $this->apiKey]
            ]);

            $details = $detailResponse->toArray();

            $plant = new Plant();
            $this->plantMapper->map($plant, $details);

            $this->em->persist($plant);
            $count++;

            $io->text('✅ ' . $plant->getCommonName());
        }

        $this->em->flush();
        file_put_contents($this->pageFile, $page + 1);

        $io->success($count . ' plantes importées! Prochaine exécution reprendra à la page ' . ($page + 1));

        return Command::SUCCESS;
    }
}
