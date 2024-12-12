<?php

class FileModel
{
    private $db;

    public function __construct()
    {
        // Database connection parameters
        $this->db = new mysqli(
            'localhost',   // hostname
            'root',        // username
            '',            // password
            'file_management_system', // database
            3306           // port
        );

        // Check connection
        if ($this->db->connect_error) {
            die("Database connection failed: " . $this->db->connect_error);
        }
    }

    /**
     * Get all files or filter by criteria
     *
     * @param array $filters
     * @return array
     */
    public function getFiles($filters = [])
    {
        $sql = "SELECT * FROM files WHERE 1=1";

        // Apply filters
        if (!empty($filters['file_name'])) {
            $sql .= " AND file_name LIKE '%" . $this->db->real_escape_string($filters['file_name']) . "%'";
        }

        if (!empty($filters['file_type'])) {
            $sql .= " AND file_type = '" . $this->db->real_escape_string($filters['file_type']) . "'";
        }

        $result = $this->db->query($sql);

        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    /**
     * Delete a file by ID
     *
     * @param int $id
     * @return bool
     */
    public function deleteFile($id)
    {
        $sql = "DELETE FROM files WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);

        return $stmt->execute();
    }

    /**
     * Get file details by ID
     *
     * @param int $id
     * @return array|null
     */
    public function getFileById($id)
    {
        $sql = "SELECT * FROM files WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    /**
     * Update a file by ID
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function updateFile($id, $data)
    {
        $sql = "UPDATE files SET file_name = ?, original_name = ?, file_type = ?, file_size = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param(
            "sssdi",
            $data['file_name'],
            $data['original_name'],
            $data['file_type'],
            $data['file_size'],
            $id
        );

        return $stmt->execute();
    }

    /**
     * Close database connection
     */
    public function closeConnection()
    {
        $this->db->close();
    }
}
