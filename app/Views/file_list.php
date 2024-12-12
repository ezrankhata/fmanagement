<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Uploaded Files</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        /* Basic styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }
        h1 {
            text-align: center;
            color: #333;
            margin-top: 20px;
        }
        .filter-container {
            width: 80%;
            margin: 20px auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .filter-container input, .filter-container select {
            padding: 8px 12px;
            font-size: 14px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .filter-container button {
            padding: 8px 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .filter-container button:hover {
            opacity: 0.8;
        }
        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        td {
            background-color: #f9f9f9;
        }
        a {
            text-decoration: none;
            padding: 6px 12px;
            margin: 5px;
            color: white;
            border-radius: 4px;
        }
        a.edit {
            background-color: #4CAF50;
        }
        a.delete {
            background-color: #f44336;
        }
        a:hover {
            opacity: 0.8;
        }
        .flash-message {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            margin: 10px auto;
            width: 80%;
            border-radius: 5px;
            text-align: center;
        }
        .checkbox-container {
            margin-top: 20px;
            text-align: center;
        }
        .checkbox-container input {
            margin: 0 5px;
        }
        .select-all-btn, .delete-selected-btn {
            padding: 8px 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin: 10px;
        }
        .select-all-btn:hover, .delete-selected-btn:hover {
            opacity: 0.8;
        }
        .actions-container {
            display: flex;
            justify-content: flex-start;
            gap: 10px;
        }
        /* Back Button Style */
        .back-btn {
            padding: 8px 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin: 20px;
            font-size: 16px;
            display: inline-flex;
            align-items: center;
        }
        .back-btn i {
            margin-right: 8px;
        }
        .back-btn:hover {
            opacity: 0.8;
        }
    </style>
</head>
<body>

    <h1>Uploaded Files</h1>

    <!-- Back to Upload Files Screen Button -->
    <button class="back-btn" onclick="window.location.href='<?= base_url('file-upload'); ?>'">
        <i class="fas fa-arrow-left"></i> Back to Upload Files
    </button>

    <!-- Flash Message for Success -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="flash-message"><?= session()->getFlashdata('success'); ?></div>
    <?php endif; ?>

    <!-- Filter Section -->
    <div class="filter-container">
        <input type="text" id="file-name-filter" placeholder="Filter by file name" />
        <select id="file-type-filter">
            <option value="">Filter by file type</option>
            <option value="application/pdf">PDF</option>
            <option value="image/png">PNG</option>
            <option value="image/jpeg">JPEG</option>
        </select>
        <input type="number" id="file-size-filter" placeholder="Max file size (KB)" />
        <button id="reset-filters">Reset Filters</button>
    </div>

    <!-- Select All and Delete Selected Buttons -->
    <div class="checkbox-container">
        <button class="select-all-btn" id="select-all">Select All</button>
        <button class="delete-selected-btn" id="delete-selected">Delete Selected</button>
    </div>

    <!-- File List Table -->
    <table id="file-table">
        <thead>
            <tr>
                <th>Select</th>
                <th>File Name</th>
                <th>Original Name</th>
                <th>File Type</th>
                <th>File Size</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($files as $file): ?>
                <tr class="file-row" data-file-name="<?= esc($file['file_name']); ?>" data-file-type="<?= esc($file['file_type']); ?>" data-file-size="<?= $file['file_size']; ?>">
                    <td><input type="checkbox" class="file-checkbox" value="<?= $file['id']; ?>" /></td>
                    <td><?= esc($file['file_name']); ?></td>
                    <td><?= esc($file['original_name']); ?></td>
                    <td><?= esc($file['file_type']); ?></td>
                    <td><?= number_format($file['file_size'] / 1024, 2); ?> KB</td> <!-- Display size in KB -->
                    <td class="actions-container">
                        <a href="<?= base_url('file-upload/edit/' . $file['id']); ?>" class="edit">Edit</a>
                        <a href="/file-upload/delete/<?= $file['id']; ?>" class="delete" onclick="return confirm('Are you sure you want to delete this file?')">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <script>
        // Function to apply filters
        function applyFilters() {
            const fileNameFilter = document.getElementById('file-name-filter').value.toLowerCase();
            const fileTypeFilter = document.getElementById('file-type-filter').value;
            const fileSizeFilter = document.getElementById('file-size-filter').value;

            const rows = document.querySelectorAll('.file-row');

            rows.forEach(row => {
                const fileName = row.dataset.fileName.toLowerCase();
                const fileType = row.dataset.fileType;
                const fileSize = parseInt(row.dataset.fileSize) / 1024; // KB

                // Check if the row matches the filters
                const matchesName = fileName.includes(fileNameFilter);
                const matchesType = fileType.includes(fileTypeFilter) || !fileTypeFilter;
                const matchesSize = !fileSizeFilter || fileSize <= parseInt(fileSizeFilter);

                if (matchesName && matchesType && matchesSize) {
                    row.style.display = ''; // Show the row
                } else {
                    row.style.display = 'none'; // Hide the row
                }
            });
        }

        // Event listeners for filters
        document.getElementById('file-name-filter').addEventListener('input', applyFilters);
        document.getElementById('file-type-filter').addEventListener('change', applyFilters);
        document.getElementById('file-size-filter').addEventListener('input', applyFilters);

        // Reset Filters button
        document.getElementById('reset-filters').addEventListener('click', function() {
            document.getElementById('file-name-filter').value = '';
            document.getElementById('file-type-filter').value = '';
            document.getElementById('file-size-filter').value = '';
            applyFilters(); // Apply after resetting
        });

        // Select All button functionality
        document.getElementById('select-all').addEventListener('click', function() {
            const checkboxes = document.querySelectorAll('.file-checkbox');
            const allChecked = [...checkboxes].every(checkbox => checkbox.checked);
            checkboxes.forEach(checkbox => checkbox.checked = !allChecked);
        });

        // Delete Selected button functionality
        document.getElementById('delete-selected').addEventListener('click', function() {
            const selectedIds = [...document.querySelectorAll('.file-checkbox:checked')].map(checkbox => checkbox.value);
            if (selectedIds.length > 0 && confirm('Are you sure you want to delete selected files?')) {
                // Send selected file IDs to the server for deletion
                // (You should implement the backend logic for batch deletion)
            }
        });
    </script>
</body>
</html>