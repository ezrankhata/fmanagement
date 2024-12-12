<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Upload</title>
    <style>
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
        .container {
            width: 80%;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        .flash-message {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            text-align: center;
        }
        .error-message {
            background-color: #f44336;
            color: white;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            text-align: center;
        }
        .file-info {
            background-color: #2196F3;
            color: white;
            padding: 10px;
            margin-top: 10px;
            border-radius: 5px;
            text-align: center;
        }
        form {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 20px;
        }
        input[type="file"] {
            padding: 10px;
            border: 2px solid #4CAF50;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        .nav-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #2196F3;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
            text-align: center;
        }
        .nav-button:hover {
            background-color: #1976D2;
        }
    </style>
</head>
<body>

    <h1>Upload a File</h1>

    <div class="container">
        <!-- Display Errors -->
        <?php if (session()->getFlashdata('errors')): ?>
            <div class="error-message">
                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                    <p><?= esc($error) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Display Success -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="flash-message">
                <p><?= session()->getFlashdata('success') ?></p>
            </div>
        <?php endif; ?>

        <!-- Display File Info after successful upload -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="file-info">
                <p><strong>Uploaded File:</strong> <?= session()->getFlashdata('fileName') ?></p>
                <p><strong>Location:</strong> <?= session()->getFlashdata('uploadDirectory') ?></p>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('file-upload') ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <input type="file" name="userfile" required>
            <button type="submit">Upload</button>
        </form>

        <!-- Navigation Button -->
        <div style="margin-top: 20px;">
            <a href="<?= base_url('file-upload/view-files') ?>" class="nav-button">View Uploaded Files</a>
        </div>
    </div>

</body>
</html>
