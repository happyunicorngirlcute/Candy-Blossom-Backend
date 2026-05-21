<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class PerenualApiService
{
    private static array $localDatabase = [
        10001 => [
            'id' => 10001,
            'common_name' => 'Rose',
            'scientific_name' => ['Rosa'],
            'other_name' => [],
            'family' => 'Rosaceae',
            'cycle' => 'Perennial',
            'watering' => 'Average',
            'sunlight' => ['Full sun'],
            'default_image' => [
                'original_url' => 'https://images.unsplash.com/photo-1518709268805-4e9042af9f23?q=80&w=600&auto=format&fit=crop',
                'regular_url' => 'https://images.unsplash.com/photo-1518709268805-4e9042af9f23?q=80&w=600&auto=format&fit=crop',
                'thumbnail' => 'https://images.unsplash.com/photo-1518709268805-4e9042af9f23?q=80&w=150&auto=format&fit=crop'
            ],
            'description' => 'A woody perennial flowering plant of the genus Rosa, in the family Rosaceae. They form a group of plants that can be erect shrubs, climbing, or trailing, with stems that are often armed with sharp prickles.'
        ],
        10002 => [
            'id' => 10002,
            'common_name' => 'Monstera Deliciosa',
            'scientific_name' => ['Monstera deliciosa'],
            'other_name' => ['Swiss Cheese Plant'],
            'family' => 'Araceae',
            'cycle' => 'Perennial',
            'watering' => 'Frequent',
            'sunlight' => ['Part shade'],
            'default_image' => [
                'original_url' => 'https://images.unsplash.com/photo-1614594975525-e45190c55d0b?q=80&w=600&auto=format&fit=crop',
                'regular_url' => 'https://images.unsplash.com/photo-1614594975525-e45190c55d0b?q=80&w=600&auto=format&fit=crop',
                'thumbnail' => 'https://images.unsplash.com/photo-1614594975525-e45190c55d0b?q=80&w=150&auto=format&fit=crop'
            ],
            'description' => 'Famous for its natural leaf-holes (fenestrations), the Monstera Deliciosa is a stunning tropical houseplant that adds a lush jungle feel to any indoor space.'
        ],
        10003 => [
            'id' => 10003,
            'common_name' => 'Boston Fern',
            'scientific_name' => ['Nephrolepis exaltata'],
            'other_name' => ['Sword Fern'],
            'family' => 'Lomariopsidaceae',
            'cycle' => 'Perennial',
            'watering' => 'Frequent',
            'sunlight' => ['Part shade'],
            'default_image' => [
                'original_url' => 'https://images.unsplash.com/photo-1545241047-6083a3684587?q=80&w=600&auto=format&fit=crop',
                'regular_url' => 'https://images.unsplash.com/photo-1545241047-6083a3684587?q=80&w=600&auto=format&fit=crop',
                'thumbnail' => 'https://images.unsplash.com/photo-1545241047-6083a3684587?q=80&w=150&auto=format&fit=crop'
            ],
            'description' => 'A classic indoor fern featuring arching fronds of bright green leaves. It excels in humid environments and indirect light.'
        ],
        10004 => [
            'id' => 10004,
            'common_name' => 'Snake Plant',
            'scientific_name' => ['Dracaena trifasciata'],
            'other_name' => ["Mother-in-law's tongue"],
            'family' => 'Asparagaceae',
            'cycle' => 'Perennial',
            'watering' => 'Minimum',
            'sunlight' => ['Part shade', 'Full shade'],
            'default_image' => [
                'original_url' => 'https://images.unsplash.com/photo-1596547609652-9cf5d8d76921?q=80&w=600&auto=format&fit=crop',
                'regular_url' => 'https://images.unsplash.com/photo-1596547609652-9cf5d8d76921?q=80&w=600&auto=format&fit=crop',
                'thumbnail' => 'https://images.unsplash.com/photo-1596547609652-9cf5d8d76921?q=80&w=150&auto=format&fit=crop'
            ],
            'description' => 'One of the hardiest houseplants. It features upright, sword-like green leaves with yellow margins and survives with minimal watering and in various lighting conditions.'
        ],
        10005 => [
            'id' => 10005,
            'common_name' => 'Peace Lily',
            'scientific_name' => ['Spathiphyllum'],
            'other_name' => [],
            'family' => 'Araceae',
            'cycle' => 'Perennial',
            'watering' => 'Average',
            'sunlight' => ['Part shade'],
            'default_image' => [
                'original_url' => 'https://images.unsplash.com/photo-1593696140826-c58b021acf8b?q=80&w=600&auto=format&fit=crop',
                'regular_url' => 'https://images.unsplash.com/photo-1593696140826-c58b021acf8b?q=80&w=600&auto=format&fit=crop',
                'thumbnail' => 'https://images.unsplash.com/photo-1593696140826-c58b021acf8b?q=80&w=150&auto=format&fit=crop'
            ],
            'description' => 'A popular indoor plant known for its striking white flower-like spanthes and glossy green leaves. They are excellent air purifiers.'
        ],
        10006 => [
            'id' => 10006,
            'common_name' => 'Spider Plant',
            'scientific_name' => ['Chlorophytum comosum'],
            'other_name' => ['Ribbon Plant'],
            'family' => 'Asphodelaceae',
            'cycle' => 'Perennial',
            'watering' => 'Average',
            'sunlight' => ['Part shade'],
            'default_image' => [
                'original_url' => 'https://images.unsplash.com/photo-1572656631137-7935297eff55?q=80&w=600&auto=format&fit=crop',
                'regular_url' => 'https://images.unsplash.com/photo-1572656631137-7935297eff55?q=80&w=600&auto=format&fit=crop',
                'thumbnail' => 'https://images.unsplash.com/photo-1572656631137-7935297eff55?q=80&w=150&auto=format&fit=crop'
            ],
            'description' => 'Featuring long, thin, arching green-and-white variegated leaves, this plant is exceptionally easy to grow and produces adorable spider-like plantlets.'
        ],
        10007 => [
            'id' => 10007,
            'common_name' => 'Aloe Vera',
            'scientific_name' => ['Aloe vera'],
            'other_name' => ['Burn Plant'],
            'family' => 'Asphodelaceae',
            'cycle' => 'Perennial',
            'watering' => 'Minimum',
            'sunlight' => ['Full sun'],
            'default_image' => [
                'original_url' => 'https://images.unsplash.com/photo-1567306226416-28f0efdc88ce?q=80&w=600&auto=format&fit=crop',
                'regular_url' => 'https://images.unsplash.com/photo-1567306226416-28f0efdc88ce?q=80&w=600&auto=format&fit=crop',
                'thumbnail' => 'https://images.unsplash.com/photo-1567306226416-28f0efdc88ce?q=80&w=150&auto=format&fit=crop'
            ],
            'description' => 'A succulent plant species of the genus Aloe. It grows wild in tropical climates and is cultivated for agricultural and medicinal uses, notably for soothing skin.'
        ],
        10008 => [
            'id' => 10008,
            'common_name' => 'English Ivy',
            'scientific_name' => ['Hedera helix'],
            'other_name' => ['Common Ivy'],
            'family' => 'Araliaceae',
            'cycle' => 'Perennial',
            'watering' => 'Average',
            'sunlight' => ['Part shade', 'Full shade'],
            'default_image' => [
                'original_url' => 'https://images.unsplash.com/photo-1512428559087-560fa5ceab42?q=80&w=600&auto=format&fit=crop',
                'regular_url' => 'https://images.unsplash.com/photo-1512428559087-560fa5ceab42?q=80&w=600&auto=format&fit=crop',
                'thumbnail' => 'https://images.unsplash.com/photo-1512428559087-560fa5ceab42?q=80&w=150&auto=format&fit=crop'
            ],
            'description' => 'A beautiful evergreen climbing vine that clings tightly to walls, trellises, or cascades elegantly out of hanging baskets.'
        ],
        10009 => [
            'id' => 10009,
            'common_name' => 'Lavender',
            'scientific_name' => ['Lavandula'],
            'other_name' => [],
            'family' => 'Lamiaceae',
            'cycle' => 'Perennial',
            'watering' => 'Minimum',
            'sunlight' => ['Full sun'],
            'default_image' => [
                'original_url' => 'https://images.unsplash.com/photo-1528183429752-a97d0bf99b5a?q=80&w=600&auto=format&fit=crop',
                'regular_url' => 'https://images.unsplash.com/photo-1528183429752-a97d0bf99b5a?q=80&w=600&auto=format&fit=crop',
                'thumbnail' => 'https://images.unsplash.com/photo-1528183429752-a97d0bf99b5a?q=80&w=150&auto=format&fit=crop'
            ],
            'description' => 'Highly aromatic shrub renowned for its beautiful purple spikes, sweet calming fragrance, and drought tolerance.'
        ],
        10010 => [
            'id' => 10010,
            'common_name' => 'Fiddle Leaf Fig',
            'scientific_name' => ['Ficus lyrata'],
            'other_name' => ['Banjo Fig'],
            'family' => 'Moraceae',
            'cycle' => 'Perennial',
            'watering' => 'Average',
            'sunlight' => ['Part shade'],
            'default_image' => [
                'original_url' => 'https://images.unsplash.com/photo-1597055181300-e3633a207518?q=80&w=600&auto=format&fit=crop',
                'regular_url' => 'https://images.unsplash.com/photo-1597055181300-e3633a207518?q=80&w=600&auto=format&fit=crop',
                'thumbnail' => 'https://images.unsplash.com/photo-1597055181300-e3633a207518?q=80&w=150&auto=format&fit=crop'
            ],
            'description' => 'A popular interior design tree featuring large, glossy, violin-shaped leaves on sleek structural branches.'
        ]
    ];

    public function __construct(
        private HttpClientInterface $httpClient,
        private string $apiKey
    ) {}

    public function searchPlant(string $name, int $page = 1): array
    {
        try {
            $response = $this->httpClient->request('GET', 'https://perenual.com/api/species-list', [
                'query' => [
                    'key' => $this->apiKey,
                    'q' => $name,
                    'page' => $page
                ]
            ]);

            $statusCode = $response->getStatusCode();
            if ($statusCode === 429) {
                return $this->searchLocalFallback($name);
            }

            if ($statusCode >= 400) {
                throw new \RuntimeException("Perenual API returned error code {$statusCode}.");
            }

            $data = $response->toArray();
            
            if (empty($data['data']) || !isset($data['data'])) {
                return $this->searchLocalFallback($name);
            }

            return $data;
        } catch (\Exception $e) {
            return $this->searchLocalFallback($name);
        }
    }

    public function getPlantDetails(int $id): array
    {
        if (isset(self::$localDatabase[$id])) {
            return self::$localDatabase[$id];
        }

        try {
            $response = $this->httpClient->request('GET', "https://perenual.com/api/v2/species/details/{$id}", [
                'query' => [
                    'key' => $this->apiKey,
                ]
            ]);

            $statusCode = $response->getStatusCode();
            if ($statusCode === 429) {
                 $index = ($id % 10) + 10001;
                 return self::$localDatabase[$index] ?? self::$localDatabase[10001];
            }

            if ($statusCode >= 400) {
                throw new \RuntimeException("Perenual API returned error code {$statusCode}.");
            }

            return $response->toArray();
        } catch (\Exception $e) {
            $index = ($id % 10) + 10001;
            return self::$localDatabase[$index] ?? self::$localDatabase[10001];
        }
    }

    private function searchLocalFallback(string $name): array
    {
        $matching = [];
        $query = strtolower(trim($name));

        foreach (self::$localDatabase as $plant) {
            if ($query === '' || 
                str_contains(strtolower($plant['common_name']), $query) || 
                str_contains(strtolower($plant['scientific_name'][0] ?? ''), $query)) {
                $matching[] = $plant;
            }
        }

        if (empty($matching)) {
            $matching = array_values(self::$localDatabase);
        }

        return [
            'data' => $matching,
            'current_page' => 1,
            'last_page' => 1,
            'total' => count($matching)
        ];
    }
}
