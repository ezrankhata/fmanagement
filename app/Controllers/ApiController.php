<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\ApiLogModel;
use App\Models\FileUploadModel;

class ApiController extends ResourceController
{
    protected $modelName = 'App\Models\FileUploadModel'; // Replace with your model if different
    protected $format    = 'json';

    // Log each API request
    private function logApiRequest($endpoint, $requestMethod, $statusCode)
    {
        $apiLogModel = new ApiLogModel();
        $apiLogModel->logApiRequest($endpoint, $requestMethod, $statusCode);
    }

    // Upload API method
    public function upload()
    {
        // Validation rules for file upload
        $validationRule = [
    'userfile' => [
        'label' => 'File',
        'rules' => 'uploaded[userfile]'
            . '|mime_in[userfile,image/png,image/jpeg,image/jpg,application/pdf'
            . ',application/vnd.openxmlformats-officedocument.wordprocessingml.document'
            . ',application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
            . ',application/vnd.openxmlformats-officedocument.presentationml.presentation'
            . ',audio/mpeg]' // Added support for DOCX, Excel, PowerPoint, and MP3
            . '|max_size[userfile,5120]', // Limit file size to 5MB
    ],
];


        // Log the request
        $this->logApiRequest('upload', $this->request->getMethod(), 400);  // Default log for failed validation

        // If validation fails, return error response
        if (!$this->validate($validationRule)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        // Get the uploaded file
        $file = $this->request->getFile('userfile');

        // Check if the file is valid and hasn't been moved yet
        if ($file->isValid() && !$file->hasMoved()) {
            // Generate a unique name for the file
            $uniqueName = time() . '_' . bin2hex(random_bytes(5)) . '.' . $file->getExtension();

            // Determine the directory based on MIME type
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $fileMime = finfo_file($finfo, $file->getTempName());
            finfo_close($finfo);

            // Determine the directory for the file type
            // Validate that the file MIME type matches the expected one
		if (strpos($fileMime, 'image') !== false) {
			// It's an image, we can allow it
			$directory = 'uploads/images';
		} elseif (strpos($fileMime, 'pdf') !== false) {
			// It's a PDF, we can allow it
			$directory = 'uploads/documents';
		} elseif (strpos($fileMime, 'vnd.openxmlformats-officedocument.wordprocessingml.document') !== false) {
			// It's a DOCX (MS Word) file, we can allow it
			$directory = 'uploads/documents';
		} elseif (strpos($fileMime, 'vnd.openxmlformats-officedocument.spreadsheetml.sheet') !== false) {
			// It's an Excel file, we can allow it
			$directory = 'uploads/documents';
		} elseif (strpos($fileMime, 'vnd.openxmlformats-officedocument.presentationml.presentation') !== false) {
			// It's a PowerPoint file, we can allow it
			$directory = 'uploads/documents';
		} elseif (strpos($fileMime, 'audio/mpeg') !== false) {
			// It's an MP3 (audio) file, we can allow it
			$directory = 'uploads/audio';
		} else {
			// Invalid file type
			return redirect()->back()->with('error', 'Invalid file type.');
		}


            // Ensure the directory exists
            if (!is_dir($directory)) {
                mkdir($directory, 0755, true);
            }

            // Move the file to the appropriate directory
            $file->move($directory, $uniqueName);

            // Prepare file metadata for database insertion
            $fileData = [
                'file_name'     => $uniqueName,
                'original_name' => $file->getClientName(),
                'file_type'     => $file->getClientMimeType(),
                'file_size'     => $file->getSize(),
                'directory_id'  => null, // Optional, use if applicable
            ];

            // Insert the file metadata into the database
            $fileModel = new FileUploadModel();
            if ($fileModel->save($fileData)) {
                // Log successful request
                $this->logApiRequest('upload', $this->request->getMethod(), 201);  // 201 Created response code
                return $this->respondCreated([
                    'message' => 'File uploaded successfully!',
                    'data'    => $fileData
                ]);
            } else {
                // Log failed request
                $this->logApiRequest('upload', $this->request->getMethod(), 500);  // 500 Internal server error
                return $this->failServerError('Failed to store file metadata in the database.');
            }
        }

        // Log failed request
        $this->logApiRequest('upload', $this->request->getMethod(), 400);  // 400 Bad request
        return $this->fail('File upload failed.');
    }

    // View files API method
    public function viewFiles()
    {
        $fileModel = new FileUploadModel();
        $files = $fileModel->findAll();

        // Log the request
        $this->logApiRequest('viewFiles', $this->request->getMethod(), 200);  // 200 OK response code
        return $this->respond($files);
    }

    // Delete file API method
    public function delete($id)
    {
        $fileModel = new FileUploadModel();
        $fileData = $fileModel->find($id);

        // Log the request
        $this->logApiRequest('delete', $this->request->getMethod(), 404); // Default 404 if file not found

        if (!$fileData) {
            return $this->failNotFound('File not found.');
        }

        // Path to the file
        $filePath = 'uploads/' . $fileData['file_name'];

        // Delete the physical file from the server
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        // Delete the file entry from the database
        $fileModel->delete($id);

        // Log successful deletion
        $this->logApiRequest('delete', $this->request->getMethod(), 200);  // 200 OK response code
        return $this->respondDeleted([
            'message' => 'File deleted successfully!',
            'data'    => $fileData
        ]);
    }
}
