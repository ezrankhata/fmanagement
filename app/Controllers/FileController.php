<?php

require_once 'FileModel.php';

class FileController
{
    private $fileModel;

    public function __construct()
    {
        $this->fileModel = new FileModel();
    }

    // Display all files
    public function index()
    {
        $filters = [
            'file_name' => $_GET['file_name'] ?? null,
            'file_type' => $_GET['file_type'] ?? null,
        ];
        $files = $this->fileModel->getFiles($filters);

        require 'views/files_view.php'; // Pass $files to your view
    }

    // Delete file by ID
    public function delete($id)
    {
        if ($this->fileModel->deleteFile($id)) {
            $_SESSION['success'] = 'File deleted successfully.';
        } else {
            $_SESSION['error'] = 'Failed to delete the file.';
        }

        header('Location: ' . base_url('file-upload'));
        exit();
    }

    // Display edit form
    public function edit($id)
    {
        $file = $this->fileModel->getFileById($id);

        if (!$file) {
            $_SESSION['error'] = 'File not found.';
            header('Location: ' . base_url('file-upload'));
            exit();
        }

        require 'views/edit_file_view.php'; // Create this view for editing
    }

    // Update file
    public function update($id)
    {
        $data = [
            'file_name' => $_POST['file_name'],
            'original_name' => $_POST['original_name'],
            'file_type' => $_POST['file_type'],
            'file_size' => $_POST['file_size'],
        ];

        if ($this->fileModel->updateFile($id, $data)) {
            $_SESSION['success'] = 'File updated successfully.';
        } else {
            $_SESSION['error'] = 'Failed to update the file.';
        }

        header('Location: ' . base_url('file-upload'));
        exit();
    }
}
