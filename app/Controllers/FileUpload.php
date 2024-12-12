<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\FileUploadModel;

class FileUpload extends Controller
{
    // Display the file upload form
    public function index()
    {
        return view('upload_form'); // Load the file upload form view
    }

    // View files
    public function viewFiles()
    {
        // Load the FileUploadModel
        $fileModel = new FileUploadModel();

        // Fetch all files from the model
        $files = $fileModel->findAll();

        // Add pagination if there are many files
        $pager = \Config\Services::pager();
        $data['files'] = $files;
        $data['pager'] = $pager;  // Include pagination in the view

        // Pass the data to the view
        return view('file_list', $data);
    }

    // Method to upload a new file
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


    // If validation fails, redirect back with errors
    if (!$this->validate($validationRule)) {
        return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
    }

    // Get the uploaded file
    $file = $this->request->getFile('userfile');

    // Check if the file is valid and hasn't been moved yet
    if ($file->isValid() && !$file->hasMoved()) {
        // Generate a unique name for the file using the current timestamp and a random string
        $uniqueName = time() . '_' . bin2hex(random_bytes(5)) . '.' . $file->getExtension();

        // Use finfo to validate the file content
        $finfo = finfo_open(FILEINFO_MIME_TYPE); // Open fileinfo
        $fileMime = finfo_file($finfo, $file->getTempName()); // Get MIME type based on content

        // Close the fileinfo resource
        finfo_close($finfo);

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


        // Ensure the directory exists, create it if not
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true); // Create directory with appropriate permissions
        }

        // Move the file to the appropriate directory with the unique name
        $file->move($directory, $uniqueName);

        // Prepare file metadata to be saved in the database
        $fileData = [
            'file_name'     => $uniqueName,
            'original_name' => $file->getClientName(),
            'file_type'     => $file->getClientMimeType(),
            'file_size'     => $file->getSize(),
            'directory_id'  => null, // You can set the directory ID if applicable
        ];

        // Load the FileUploadModel
        $fileModel = new FileUploadModel();

        // Insert the file metadata into the database
        if ($fileModel->save($fileData)) {
            return redirect()->back()
                ->with('success', 'File uploaded and metadata stored successfully!')
                ->with('uploadDirectory', $directory)
                ->with('fileName', $uniqueName);
        } else {
            return redirect()->back()->with('error', 'Failed to store file metadata in the database.');
        }
    }

    return redirect()->back()->with('error', 'Failed to upload the file.');
}


    // Method to edit a file's metadata (Update)
    public function edit($id)
    {
        // Load the FileUploadModel
        $fileModel = new FileUploadModel();

        // Get the file's current data by its ID
        $file = $fileModel->find($id);

        // Check if the file exists
        if (!$file) {
            // If the file is not found, throw a page not found exception
            throw new \CodeIgniter\Exceptions\PageNotFoundException('File not found.');
        }

        // Pass the file data to the view
        return view('edit_file', ['file' => $file]);
    }

    // Method to update file metadata
    public function update($id)
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


        // If validation fails, redirect back with errors
        if (!$this->validate($validationRule)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Get the uploaded file
        $file = $this->request->getFile('userfile');

        // Load the FileUploadModel
        $fileModel = new FileUploadModel();

        // Get the file's current data by its ID
        $fileData = $fileModel->find($id);

        // Check if the file exists
        if (!$fileData) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('File not found.');
        }

        // If a new file is uploaded
        if ($file->isValid() && !$file->hasMoved()) {
            // Delete the old file if a new one is uploaded
            unlink('uploads/' . $fileData['file_name']); // Delete the old file

            // Generate a new unique name for the file
            $uniqueName = time() . '_' . bin2hex(random_bytes(5)) . '.' . $file->getExtension();

            $mimeType = $file->getMimeType();

	if (strpos($mimeType, 'image') !== false) {
		// It's an image, we can allow it
		$directory = 'uploads/images';
	} elseif (strpos($mimeType, 'pdf') !== false) {
		// It's a PDF, we can allow it
		$directory = 'uploads/documents';
	} elseif (strpos($mimeType, 'wordprocessingml.document') !== false) {
		// It's a DOCX (Word document), we can allow it
		$directory = 'uploads/documents';
	} elseif (strpos($mimeType, 'spreadsheetml.sheet') !== false) {
		// It's an Excel file, we can allow it
		$directory = 'uploads/documents';
	} elseif (strpos($mimeType, 'presentationml.presentation') !== false) {
		// It's a PowerPoint file, we can allow it
		$directory = 'uploads/documents';
	} elseif (strpos($mimeType, 'audio/mpeg') !== false) {
		// It's an MP3 (audio) file, we can allow it
		$directory = 'uploads/audio';
	} else {
		// Invalid file type
		return redirect()->back()->with('error', 'Invalid file type.');
}


            // Ensure the directory exists, create it if not
            if (!is_dir($directory)) {
                mkdir($directory, 0755, true);
            }

            // Move the file to the appropriate directory with the unique name
            $file->move($directory, $uniqueName);

            // Update file metadata in the database
            $updatedData = [
                'file_name'     => $uniqueName,
                'original_name' => $file->getClientName(),
                'file_type'     => $file->getClientMimeType(),
                'file_size'     => $file->getSize(),
            ];

            // Update the file data in the database
            $fileModel->update($id, $updatedData);

            return redirect()->to('/file-upload/view-files')->with('success', 'File updated successfully!');
        }

        // If no new file is uploaded, just return an error message
        return redirect()->back()->with('error', 'Failed to update file, no new file uploaded.');
    }

    // Method to delete a file
    public function delete($id)
    {
        // Load the FileUploadModel
        $fileModel = new FileUploadModel();

        // Get the file data by its ID
        $fileData = $fileModel->find($id);

        // Check if the file exists in the database
        if (!$fileData) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('File not found.');
        }

        // Path to the file
        $filePath = 'uploads/' . $fileData['file_name'];

        // Delete the physical file from the server
        if (file_exists($filePath)) {
            unlink($filePath); // Delete the physical file
        }

        // Now delete the file entry from the database
        $fileModel->delete($id);

        // Redirect to the file list with a success message
        return redirect()->to('/file-upload/view-files')->with('success', 'File deleted successfully!');
    }

    // Method to handle bulk deletion
    public function bulkDelete()
    {
        $fileIds = $this->request->getPost('file_ids'); // Array of file IDs to delete

        if (!empty($fileIds)) {
            $fileModel = new FileUploadModel();

            // Delete files from the database and server
            foreach ($fileIds as $id) {
                $fileData = $fileModel->find($id);

                // Check if file exists before deletion
                if ($fileData) {
                    // Delete the physical file
                    $filePath = 'uploads/' . $fileData['file_name'];
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }

                    // Delete the file entry from the database
                    $fileModel->delete($id);
                }
            }

            return redirect()->to('/file-upload/view-files')->with('success', 'Selected files deleted successfully!');
        }

        return redirect()->to('/file-upload/view-files')->with('error', 'No files selected for deletion.');
    }
}

?>
