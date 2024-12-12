<?php

namespace App\Models;

use CodeIgniter\Model;

class ApiLogModel extends Model
{
    protected $table = 'api_logs';
    protected $primaryKey = 'id';
    protected $allowedFields = ['endpoint', 'request_method', 'status_code', 'log_date'];
    protected $useTimestamps = false; // We manage the timestamp manually
    protected $dateFormat = 'datetime'; // Format of the date

    // Insert log entry into the database
    public function logApiRequest($endpoint, $requestMethod, $statusCode)
    {
        return $this->insert([
            'endpoint'    => $endpoint,
            'request_method' => $requestMethod,
            'status_code'   => $statusCode,
            'log_date'     => date('Y-m-d H:i:s')
        ]);
    }
}
