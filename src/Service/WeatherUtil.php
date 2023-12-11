<?php
declare(strict_types=1);

namespace App\Service;

use App\Entity\Location;
use App\Entity\Measurement;
use App\Repository\LocationRepository;
use App\Repository\MeasurementRepository;

class WeatherUtil
{
  public function __construct(MeasurementRepository $measurementRepository, LocationRepository $locationRepository)
  {
      $this->measurementRepository = $measurementRepository;
      $this->locationRepository = $locationRepository;
  }

  public function getWeatherForLocation(Location $location): array
  {
    $measurements = $this->measurementRepository->findByLocation($location);
    return $measurements;
  }

  public function getWeatherForCountryAndCity(string $country, string $city): array
  {
    $location = $this->locationRepository->findOneBy(
      [
        'country' => $country,
        'city' => $city,
      ]
    );

    $measurements = $this->getWeatherForLocation($location);
    return $measurements;
  }
}