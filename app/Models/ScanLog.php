<?php

namespace App\Models;

use CodeIgniter\Model;

class ScanLog extends Model
{
    protected $table = 'scan_logs';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['pet_id', 'user_id', 'ip_address', 'city', 'country', 'latitude', 'longitude', 'user_agent', 'device_type'];
    protected $useTimestamps = true;
    protected $createdField = 'scanned_at';

    public function getPet()
    {
        return $this->belongsTo(Pet::class, 'pet_id', 'id');
    }

    public function getUser()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
