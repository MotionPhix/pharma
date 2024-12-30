<?php

namespace Database\Seeders;

use App\Models\Pharmacy;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $services = [
      ['name' => 'Prescription Refills', 'description' => 'Refill your prescriptions easily and quickly.'],
      ['name' => 'Vaccinations', 'description' => 'Get vaccinated for various diseases.'],
      ['name' => 'Health Consultations', 'description' => 'Consult with our health experts.'],
      ['name' => 'Chronic Disease Management', 'description' => 'Comprehensive care for chronic conditions like diabetes and hypertension.'],
      ['name' => 'Medication Therapy Management', 'description' => 'Optimize your medication regimen with expert advice.'],
      ['name' => 'Over-the-Counter Advice', 'description' => 'Get professional advice on over-the-counter medications.'],
      ['name' => 'Travel Vaccinations', 'description' => 'Ensure you are travel-ready with necessary vaccinations.'],
      ['name' => 'Smoking Cessation Support', 'description' => 'Guidance and support to help you quit smoking.'],
      ['name' => 'Home Delivery Services', 'description' => 'Convenient delivery of medications to your doorstep.'],
      ['name' => 'Dietary Supplements Guidance', 'description' => 'Expert advice on choosing the right dietary supplements.'],
      ['name' => 'Blood Pressure Monitoring', 'description' => 'Accurate and timely blood pressure checks.'],
      ['name' => 'Diabetes Screening', 'description' => 'Early detection and management of diabetes.'],
      ['name' => 'Flu Shots', 'description' => 'Seasonal flu vaccinations for all age groups.'],
      ['name' => 'First Aid Supplies', 'description' => 'Provision of essential first aid kits and supplies.'],
      ['name' => 'Health Workshops', 'description' => 'Educational workshops on various health topics.']
    ];

    $pharmacies = Pharmacy::all();

    foreach ($pharmacies as $pharmacy) {
      $assignedServices = $pharmacy->services()->count();

      if ($assignedServices < 4) {
        $missingServicesCount = 4 - $assignedServices;
        $availableServices = collect($services)->filter(function ($service) use ($pharmacy) {
          return !$pharmacy->services()->where('name', $service['name'])->exists();
        })->random($missingServicesCount);

        foreach ($availableServices as $service) {
          $pharmacy->services()->create([
            'name' => $service['name'],
            'description' => $service['description'],
          ]);
        }
      }
    }
  }
}
