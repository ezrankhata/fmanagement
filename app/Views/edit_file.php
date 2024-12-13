<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit File</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-top: 30px;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        .file-details {
            background-color: #f9f9f9;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .file-details p {
            margin: 5px 0;
            color: #555;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #333;
        }

        .form-group input[type="file"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            background-color: #f9f9f9;
        }

        .form-group button {
            width: 100%;
            padding: 15px;
            background-color: #28a745; /* Apple Green */
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        .form-group button:hover {
            background-color: #218838; /* Darker Apple Green */
        }

        .back-link {
            display: block;
            margin-top: 20px;
            text-align: center;
            font-size: 16px;
        }

        .back-link a {
            color: #e74c3c; /* Red */
            text-decoration: none;
            font-weight: 500;
        }

        .back-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Edit File: <?= esc($file['original_name']) ?></h1>

        <div class="file-details">
            <p><strong>Current File Name:</strong> <?= esc($file['original_name']) ?></p>
            <p><strong>File Type:</strong> <?= esc($file['file_type']) ?></p>
            <p><strong>File Size:</strong> <?= esc($file['file_size']) ?> bytes</p>
        </div>

        <!-- File update form -->
        <form action="<?= base_url('file-upload/update/' . $file['id']) ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <div class="form-group">
                <label for="userfile">Upload a new file</label>
                <input type="file" name="userfile" id="userfile" required>
            </div>

            <div class="form-group">
                <button type="submit">Update File</button>
            </div>
        </form>

        <div class="back-link">
            <a href="<?= base_url('file-upload/view-files') ?>">Back to file list</a>
        </div>
    </div>

</body>
</html>
