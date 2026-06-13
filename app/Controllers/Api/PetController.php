<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Models\Pet;
use App\Services\PetService;
use App\Services\AuditService;

class PetController extends ResourceController
{
    protected $modelName = 'App\Models\Pet';
    protected $format = 'json';
    protected $petService;
    protected $auditService;

    public function __construct()
    {
        $this->petService = new PetService();
        $this->auditService = new AuditService();
    }

    public function index()
    {
        $userId = $this->getUserIdFromToken();
        if (!$userId) {
            return $this->failUnauthorized('Unauthorized');
        }

        $pets = $this->model->where('user_id', $userId)->findAll();
        return $this->respond(['pets' => $pets]);
    }

    public function show($id = null)
    {
        $userId = $this->getUserIdFromToken();
        if (!$userId) {
            return $this->failUnauthorized('Unauthorized');
        }

        $pet = $this->model->find($id);
        if (!$pet || $pet['user_id'] != $userId) {
            return $this->failNotFound('Pet not found');
        }

        $pet = $this->petService->getPetDetails($id, $userId);
        return $this->respond(['pet' => $pet]);
    }

    public function create()
    {
        $userId = $this->getUserIdFromToken();
        if (!$userId) {
            return $this->failUnauthorized('Unauthorized');
        }

        $rules = [
            'qr_tag_id' => 'required|numeric',
            'pet_name' => 'required|string|max_length[100]',
            'pet_type' => 'required|in_list[dog,cat,bird,rabbit,other]',
            'breed' => 'required|string|max_length[100]',
            'gender' => 'required|in_list[male,female]',
            'dob' => 'required|valid_date[Y-m-d]',
        ];

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $data = $this->request->getJSON(true);
        $data['user_id'] = $userId;
        $data['status'] = 'active';

        $petId = $this->petService->createPet($data);

        return $this->respondCreated([
            'message' => 'Pet created successfully',
            'pet_id' => $petId,
        ]);
    }

    public function update($id = null)
    {
        $userId = $this->getUserIdFromToken();
        if (!$userId) {
            return $this->failUnauthorized('Unauthorized');
        }

        $pet = $this->model->find($id);
        if (!$pet || $pet['user_id'] != $userId) {
            return $this->failNotFound('Pet not found');
        }

        $data = $this->request->getJSON(true);
        $this->model->update($id, $data);

        return $this->respond([
            'message' => 'Pet updated successfully',
        ]);
    }

    public function delete($id = null)
    {
        $userId = $this->getUserIdFromToken();
        if (!$userId) {
            return $this->failUnauthorized('Unauthorized');
        }

        $pet = $this->model->find($id);
        if (!$pet || $pet['user_id'] != $userId) {
            return $this->failNotFound('Pet not found');
        }

        $this->model->delete($id);

        return $this->respondDeleted([
            'message' => 'Pet deleted successfully',
        ]);
    }

    public function addVaccination($petId = null)
    {
        $userId = $this->getUserIdFromToken();
        if (!$userId) {
            return $this->failUnauthorized('Unauthorized');
        }

        $pet = $this->model->find($petId);
        if (!$pet || $pet['user_id'] != $userId) {
            return $this->failNotFound('Pet not found');
        }

        $rules = [
            'vaccine_name' => 'required|string|max_length[100]',
            'vaccination_date' => 'required|valid_date[Y-m-d]',
            'next_due_date' => 'permit_empty|valid_date[Y-m-d]',
        ];

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $data = $this->request->getJSON(true);
        $this->petService->addVaccination(
            $petId,
            $data['vaccine_name'],
            $data['vaccination_date'],
            $data['next_due_date'] ?? null
        );

        return $this->respondCreated([
            'message' => 'Vaccination added successfully',
        ]);
    }

    private function getUserIdFromToken()
    {
        $token = $this->request->getHeader('Authorization');
        if (!$token) {
            return null;
        }

        $token = str_replace('Bearer ', '', $token->getValue());

        try {
            $key = config('App')->encryptionKey;
            $decoded = \Firebase\JWT\JWT::decode($token, new \Firebase\JWT\Key($key, 'HS256'));
            return $decoded->userId;
        } catch (\Exception $e) {
            return null;
        }
    }
}
