<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Models\Pet;
use App\Models\FinderMessage;
use App\Services\PetService;

class FinderController extends ResourceController
{
    protected $format = 'json';
    protected $petService;

    public function __construct()
    {
        $this->petService = new PetService();
    }

    public function lostPet($serialNumber = null)
    {
        $petModel = new Pet();
        $qrTagModel = new \App\Models\QrTag();

        $qrTag = $qrTagModel->where('serial_number', $serialNumber)->first();
        if (!$qrTag) {
            return $this->failNotFound('Pet not found');
        }

        $pet = $petModel->find($qrTag['pet_id']);
        if (!$pet) {
            return $this->failNotFound('Pet not found');
        }

        // Log scan
        $request = service('request');
        $this->petService->logScan(
            $pet['id'],
            null,
            $request->getIPAddress(),
            null,
            null,
            null,
            null,
            $request->getUserAgent()->getAgentString()
        );

        return $this->respond([
            'pet' => $pet,
            'message' => 'Pet found. Please contact owner.',
        ]);
    }

    public function sendMessage($petId = null)
    {
        $rules = [
            'finder_name' => 'required|string|max_length[100]',
            'finder_phone' => 'required|numeric|max_length[15]',
            'finder_email' => 'permit_empty|valid_email',
            'finder_message' => 'required|string',
        ];

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $petModel = new Pet();
        $pet = $petModel->find($petId);
        if (!$pet) {
            return $this->failNotFound('Pet not found');
        }

        $data = $this->request->getJSON(true);
        $data['pet_id'] = $petId;
        $data['status'] = 'new';

        $finderMessageModel = new FinderMessage();
        $messageId = $finderMessageModel->insert($data);

        // TODO: Send notification to pet owner

        return $this->respondCreated([
            'message' => 'Message sent successfully',
            'message_id' => $messageId,
        ]);
    }
}
