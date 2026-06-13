<?php

namespace App\Models;

use CodeIgniter\Model;

class Pet extends Model
{
    protected $table = 'pets';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $allowedFields = ['user_id', 'qr_tag_id', 'pet_name', 'pet_type', 'breed', 'gender', 'dob', 'color', 'weight', 'sterilized', 'profile_photo', 'status'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';
    protected $validationRules = [
        'user_id' => 'required|numeric',
        'qr_tag_id' => 'required|numeric',
        'pet_name' => 'required|string|max_length[100]',
        'pet_type' => 'required|in_list[dog,cat,bird,rabbit,other]',
        'breed' => 'required|string|max_length[100]',
        'gender' => 'required|in_list[male,female]',
        'dob' => 'required|valid_date[Y-m-d]',
        'status' => 'in_list[active,lost,deceased,inactive]',
    ];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Relationships
    public function getUser()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function getQrTag()
    {
        return $this->belongsTo(QrTag::class, 'qr_tag_id', 'id');
    }

    public function getPhotos()
    {
        return $this->hasMany(PetPhoto::class, 'pet_id', 'id');
    }

    public function getVaccinations()
    {
        return $this->hasMany(PetVaccination::class, 'pet_id', 'id');
    }

    public function getPrivacySettings()
    {
        return $this->hasOne(PetPrivacySetting::class, 'pet_id', 'id');
    }

    public function getLostReport()
    {
        return $this->hasOne(PetLostReport::class, 'pet_id', 'id');
    }

    public function getFinderMessages()
    {
        return $this->hasMany(FinderMessage::class, 'pet_id', 'id');
    }

    public function getScanLogs()
    {
        return $this->hasMany(ScanLog::class, 'pet_id', 'id');
    }

    public function getAge()
    {
        $dob = new \DateTime($this->data['dob']);
        $now = new \DateTime();
        return $now->diff($dob)->y;
    }
}
