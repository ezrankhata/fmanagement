<?php

namespace App\Models;

use CodeIgniter\Model;

class FileUploadModel extends Model
{
    // Define the table name
    protected $table = 'files';

    // Define the primary key
    protected $primaryKey = 'id';

    // Define the allowed fields for mass assignment
    protected $allowedFields = [
        'file_name',
        'original_name',
        'file_type',
        'file_size',
        'upload_date',
        'directory_id',
        'thumbnail_path'
    ];

    // Enable automatic handling of timestamps for created_at and updated_at columns
    protected $useTimestamps = true;

    // Optionally, define the date format for the timestamp (default is 'Y-m-d H:i:s')
    protected $dateFormat = 'datetime';

    // Validation rules for your model
    protected $validationRules = [
        'file_name'     => 'required|is_unique[files.file_name]', // Example of unique constraint
        'original_name' => 'required',
        'file_type'     => 'required',
        'file_size'     => 'required|is_natural_no_zero',
        'directory_id'  => 'permit_empty|is_natural_no_zero',  // If directory_id is optional and numeric
    ];

    // Custom validation messages
    protected $validationMessages = [
        'file_name' => [
            'is_unique' => 'The file name must be unique in the system.',
        ],
        'file_size' => [
            'is_natural_no_zero' => 'The file size must be a positive integer.',
        ],
        'directory_id' => [
            'is_natural_no_zero' => 'Directory ID must be a valid positive number if provided.',
        ]
    ];

    /**
     * Method to fetch filtered files based on search term, file type, and size filter.
     *
     * @param string $search Search term for file names
     * @param string $file_type Filter by file type
     * @param string $size_filter Filter by file size ('small', 'medium', 'large')
     * @return array Filtered file records
     */
    public function getFilteredFiles($search = '', $file_type = '', $size_filter = '')
    {
        $builder = $this->builder();

        // Apply the search filter if there's a search term
        if (!empty($search)) {
            $builder->like('file_name', $search); // Search by file name
        }

        // Apply the file type filter if it's provided
        if (!empty($file_type)) {
            $builder->where('file_type', $file_type); // Filter by file type
        }

        // Apply the size filter based on small, medium, or large
        if (!empty($size_filter)) {
            switch ($size_filter) {
                case 'small':
                    $builder->where('file_size <', 1024); // Files smaller than 1MB
                    break;
                case 'medium':
                    $builder->where('file_size >=', 1024); // Files between 1MB and 10MB
                    $builder->where('file_size <=', 10240);
                    break;
                case 'large':
                    $builder->where('file_size >', 10240); // Files larger than 10MB
                    break;
            }
        }

        // Return the filtered results as an array
        return $builder->get()->getResultArray();
    }
}
