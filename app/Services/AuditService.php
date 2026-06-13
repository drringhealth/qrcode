<?php

namespace App\Services;

use App\Models\AuditLog;

class AuditService
{
    protected $auditModel;

    public function __construct()
    {
        $this->auditModel = new AuditLog();
    }

    public function log($adminUserId, $module, $action, $recordId = null, $oldValues = null, $newValues = null, $description = null)
    {
        $request = service('request');

        return $this->auditModel->insert([
            'admin_user_id' => $adminUserId,
            'module' => $module,
            'action' => $action,
            'record_id' => $recordId,
            'old_values' => $oldValues ? json_encode($oldValues) : null,
            'new_values' => $newValues ? json_encode($newValues) : null,
            'description' => $description,
            'ip_address' => $request->getIPAddress(),
            'user_agent' => $request->getUserAgent()->getAgentString(),
        ]);
    }

    public function getModuleAudit($module, $limit = 100)
    {
        return $this->auditModel
            ->where('module', $module)
            ->orderBy('created_at', 'DESC')
            ->limit($limit)
            ->findAll();
    }

    public function getRecordAudit($module, $recordId, $limit = 50)
    {
        return $this->auditModel
            ->where('module', $module)
            ->where('record_id', $recordId)
            ->orderBy('created_at', 'DESC')
            ->limit($limit)
            ->findAll();
    }

    public function getUserAudit($adminUserId, $limit = 100)
    {
        return $this->auditModel
            ->where('admin_user_id', $adminUserId)
            ->orderBy('created_at', 'DESC')
            ->limit($limit)
            ->findAll();
    }
}
