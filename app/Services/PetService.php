<?php

namespace App\Services;

use App\Models\Pet;
use App\Models\PetVaccination;
use App\Models\PetPrivacySetting;
use App\Models\ScanLog;

class PetService
{
    protected $petModel;
    protected $vaccinationModel;
    protected $privacyModel;
    protected $scanLogModel;

    public function __construct()
    {
        $this->petModel = new Pet();
        $this->vaccinationModel = new PetVaccination();
        $this->privacyModel = new PetPrivacySetting();
        $this->scanLogModel = new ScanLog();
    }

    public function createPet($data)
    {
        $petId = $this->petModel->insert($data);

        // Create default privacy settings
        $this->privacyModel->insert([
            'pet_id' => $petId,
            'show_pet_name' => true,
            'show_photo' => true,
            'show_breed' => true,
            'show_age' => true,
        ]);

        return $petId;
    }

    public function addVaccination($petId, $vaccineName, $vaccinationDate, $nextDueDate = null)
    {
        return $this->vaccinationModel->insert([
            'pet_id' => $petId,
            'vaccine_name' => $vaccineName,
            'vaccination_date' => $vaccinationDate,
            'next_due_date' => $nextDueDate,
        ]);
    }

    public function getUpcomingVaccinations($petId)
    {
        return $this->vaccinationModel
            ->where('pet_id', $petId)
            ->where('next_due_date >=', date('Y-m-d'))
            ->orderBy('next_due_date', 'ASC')
            ->findAll();
    }

    public function logScan($petId, $userId, $ipAddress, $city = null, $country = null, $latitude = null, $longitude = null, $userAgent = null, $deviceType = null)
    {
        return $this->scanLogModel->insert([
            'pet_id' => $petId,
            'user_id' => $userId,
            'ip_address' => $ipAddress,
            'city' => $city,
            'country' => $country,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'user_agent' => $userAgent,
            'device_type' => $deviceType,
        ]);
    }

    public function getPetDetails($petId, $userId = null)
    {
        $pet = $this->petModel->find($petId);
        if (!$pet) return null;

        $pet['photos'] = $this->petModel->getPhotos()->find($petId);
        $pet['vaccinations'] = $this->vaccinationModel->where('pet_id', $petId)->findAll();
        $pet['privacy_settings'] = $this->privacyModel->where('pet_id', $petId)->first();
        $pet['scan_logs'] = $this->scanLogModel->where('pet_id', $petId)->orderBy('scanned_at', 'DESC')->limit(10)->findAll();

        return $pet;
    }

    public function updatePrivacySettings($petId, $settings)
    {
        return $this->privacyModel
            ->where('pet_id', $petId)
            ->update(null, $settings);
    }
}
