<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Upload</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #e9ecef;
            margin: 0;
            padding: 0;
            transition: background-color 0.5s ease;
        }
        h1 {
            text-align: center;
            color: #343a40;
            margin-top: 20px;
            font-size: 2.5em;
            transition: color 0.3s ease;
        }
        .container {
            width: 80%;
            margin: 20px auto;
            background-color: #ffffff;
            padding: 30px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            border-radius: 12px;
            transition: transform 0.3s ease;
        }
        .container:hover {
            transform: scale(1.02);
        }
        .flash-message, .error-message, .file-info {
            border-radius: 8px;
            transition: background-color 0.3s ease;
        }
        .flash-message {
            background-color: #28a745;
            color: white;
            padding: 15px;
            margin: 15px 0;
            text-align: center;
            animation: fadeIn 0.5s;
        }
        .error-message {
            background-color: #dc3545;
            color: white;
            padding: 15px;
            margin: 15px 0;
            text-align: center;
            animation: fadeIn 0.5s;
        }
        .file-info {
            background-color: #007bff;
            color: white;
            padding: 15px;
            margin-top: 15px;
            text-align: center;
            animation: fadeIn 0.5s;
        }
        form {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 20px;
        }
        input[type="file"] {
            padding: 12px;
            border: 2px solid #28a745;
            border-radius: 6px;
            margin-bottom: 20px;
            transition: border-color 0.3s ease;
        }
        input[type="file"]:hover {
            border-color: #218838;
        }
        button {
            padding: 12px 24px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }
        button:hover {
            background-color: #218838;
            transform: translateY(-2px);
        }
        .nav-button {
            display: inline-block;
            padding: 12px 24px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            margin-top: 20px;
            text-align: center;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }
        .nav-button:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
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
