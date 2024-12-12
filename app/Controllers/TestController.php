<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\Database\Config;

class TestController extends Controller
{
    public function index()
    {
        try {
            // Get the database connection
            $db = Config::connect();

            // Run a simple query to check the connection
            $query = $db->query('SELECT 1');
            $result = $query->getResult();

            // Check if the result is valid
            if ($result) {
                echo 'Database connection successful!';
            } else {
                echo 'Database connection failed.';
            }
        } catch (\Exception $e) {
            // If an error occurs, display the error message
            echo 'Database connection failed: ' . $e->getMessage();
        }
    }
}
