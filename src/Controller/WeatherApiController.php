<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use App\Entity\Measurement;
use App\Service\WeatherUtil;

class WeatherApiController extends AbstractController
{
    #[Route('/api/v1/weather', name: 'app_weather_api', methods: ['GET'])]
    public function index(
        #[MapQueryParameter] string $country,
        #[MapQueryParameter] string $city,
        #[MapQueryParameter] string $format,
        #[MapQueryParameter('twig')] bool $twig = false,
        WeatherUtil $weatherUtil,
    ): Response
    {
        $measurements = $weatherUtil->getWeatherForCountryAndCity($country, $city);

        if ($format == "json") {
            if ($twig) {
                return $this->render('weather_api/index.json.twig', [
                    'city' => $city,
                    'country' => $country,
                    'measurements' => $measurements,
                ]);
            } else {
                return $this->json([
                    'city' => $city,
                    'country' => $country,
                    'measurements' => array_map(fn(Measurement $m) => [
                        'date' => $m->getDate()->format('Y-m-d'),
                        'celsius' => $m->getCelsius(),
                        'fahrenheit' => $m->getFahrenheit(),
                    ], $measurements),
                ]);
            }
        } else if ($format == "csv") {
            if ($twig) {
                return $this->render('weather_api/index.csv.twig', [
                    'city' => $city,
                    'country' => $country,
                    'measurements' => $measurements,
                ]);
            } else {
                $csvData = [];
                $csvData[] = 'city, country, date, celsius, fahrenheit';
    
                foreach ($measurements as $measurement) {
                    $csvData[] = sprintf('%s, %s, %s, %s, %s',
                        $city,
                        $country,
                        $measurement->getDate()->format('Y-m-d'),
                        $measurement->getCelsius(),
                        $measurement->getFahrenheit()
                    );
                }
    
                $csvContent = implode('<br>', $csvData);
    
                return new Response($csvContent, 200, []);
            }
        }
    }
}
