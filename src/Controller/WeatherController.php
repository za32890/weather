<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Location;
use App\Repository\LocationRepository;
use App\Repository\MeasurementRepository;

class WeatherController extends AbstractController
{
  #[Route('/weather/{city}/{country?}', name: 'app_weather')]
  public function city(string $city, ?string $country, MeasurementRepository $repository): Response
  {
      // $measurements = $repository->findByLocation($location);
      $measurements = $repository->findByCityAndCountry($city, $country);

      
      return $this->render('weather/city.html.twig', [
          'location' => ['city' => $city, 'country' => $country],
          'measurements' => $measurements,
      ]);
  }  
}
