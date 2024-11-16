<?php
$msg = '';
$msg_class = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if a file was uploaded without errors
    if (isset($_FILES["file"]) && $_FILES["file"]["error"] == 0) {
        $target_dir = "uploads/"; // Change this to the desired directory for uploaded files
        $target_file = $target_dir . basename($_FILES["file"]["name"]);
        $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if the file is allowed (you can modify this to allow specific file types)
        $allowed_types = array("jpg", "jpeg", "png", "gif", "pdf");
        if (!in_array($file_type, $allowed_types)) {
            $msg = "Sorry, only JPG, JPEG, PNG, GIF, and PDF files are allowed.";
            $msg_class = 'alert-danger';
        } else {
            // Move the uploaded file to the specified directory
            if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
                // File upload success, now store information in the database
                $filename = $_FILES["file"]["name"];
                $filesize = $_FILES["file"]["size"];
                $filetype = $_FILES["file"]["type"];

                // Database connection
                $db_host = "localhost";
                $db_user = "root";
                $db_pass = "Ben@d!ct18";
                $db_name = "cloudhackathon";

                $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // Insert the file information into the database
                $sql = "INSERT INTO files (filename, filesize, filetype) VALUES ('$filename', $filesize, '$filetype')";

                if ($conn->query($sql) === TRUE) {
                    $msg = "The file " . basename($_FILES["file"]["name"]) . " has been uploaded and the information has been stored in the database.";
                    $msg_class = 'alert-success';
                } else {
                    $msg = "Sorry, there was an error uploading your file and storing information in the database: " . $conn->error;
                    $msg_class = 'alert-danger';
                }

                $conn->close();
            } else {
                $msg = "Sorry, there was an error uploading your file.";
                $msg_class = 'alert-danger';
            }
        }
    } else {
        $msg = "No file was uploaded.";
        $msg_class = 'alert-danger';
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <title>File Upload and Download</title>
</head>
<body>
    <div class="container mt-5">
        <h2 class="animate__animated animate__fadeInDown">Upload a File</h2>
        <?php if ($msg != ''): ?>
            <div class="alert <?php echo $msg_class; ?> animate__animated animate__fadeInUp">
                <?php echo $msg; ?>
            </div>
        <?php endif; ?>
        <form id="uploadForm" action="upload.php" method="POST" enctype="multipart/form-data" class="animate__animated animate__fadeInUp">
            <div class="mb-3">
                <label for="file" class="form-label">Select file</label>
                <input type="file" class="form-control" name="file" id="file">
            </div>
            <button type="submit" class="btn btn-primary">Upload file</button>
        </form>
        <form id="uploadForm" action="download.php" method="POST" enctype="multipart/form-data" class="animate__animated animate__fadeInUp">

            <button type="submit" class="btn btn-primary">View Uploaded files</button>
        </form>
    </div>

    <script>
        // JavaScript to validate form before submission
        document.getElementById('uploadForm').addEventListener('submit', function(event) {
            var fileInput = document.getElementById('file');
            if (fileInput.files.length === 0) {
                event.preventDefault();
                alert('Please select a file to upload.');
            }
        });
    </script>
</body>
</html>
