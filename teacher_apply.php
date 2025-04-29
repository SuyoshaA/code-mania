<?php
// teacher_apply.php
include('db.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    
    // Process file upload for resume
    $resume_path = '';
    if (isset($_FILES['resume']) && $_FILES['resume']['error'] == UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/resumes/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        $file_name = time() . "_" . basename($_FILES['resume']['name']);
        $target_path = $upload_dir . $file_name;
        if (move_uploaded_file($_FILES['resume']['tmp_name'], $target_path)) {
            $resume_path = $target_path;
        } else {
            echo "<script>alert('Error uploading resume.'); window.location.href='teacher_apply.php';</script>";
            exit();
        }
    }
    
    // Insert the application into teacher_applications
    $stmt = $conn->prepare("INSERT INTO teacher_applications (full_name, email, phone, resume_path) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $full_name, $email, $phone, $resume_path);
    if ($stmt->execute()) {
        // Send email notification to admin
        $to = "suyoshaacharya123@gmail.com";
        $subject = "New Teacher Application";
        $message = "A new teacher application has been submitted.\n\nName: $full_name\nEmail: $email\nPhone: $phone\nResume: $resume_path";
        $headers = "From: no-reply@codemania.com\r\n";
        mail($to, $subject, $message, $headers);
        
        echo "<script>alert('Application submitted successfully! We will review your application.'); window.location.href='login.php';</script>";
    } else {
        echo "<script>alert('Application submission failed.'); window.location.href='teacher_apply.php';</script>";
    }
    $stmt->close();
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Apply as Teacher - CodingMania</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 flex items-center justify-center h-screen">
  <div class="bg-white p-8 rounded-lg shadow-lg w-96">
    <h2 class="text-2xl font-bold text-center mb-4">Teacher Application</h2>
    <form action="teacher_apply.php" method="POST" enctype="multipart/form-data">
      <div class="mb-4">
        <label class="block text-gray-700 text-sm mb-2">Full Name</label>
        <input type="text" name="full_name" required class="w-full p-3 border border-gray-300 rounded-md">
      </div>
      <div class="mb-4">
        <label class="block text-gray-700 text-sm mb-2">Email</label>
        <input type="email" name="email" required class="w-full p-3 border border-gray-300 rounded-md">
      </div>
      <div class="mb-4">
        <label class="block text-gray-700 text-sm mb-2">Phone Number</label>
        <input type="text" name="phone" required class="w-full p-3 border border-gray-300 rounded-md">
      </div>
      <div class="mb-4">
        <label class="block text-gray-700 text-sm mb-2">Upload Resume</label>
        <input type="file" name="resume" required class="w-full">
      </div>
      <button type="submit" class="w-full p-3 bg-blue-600 text-white font-bold rounded-md hover:bg-blue-700">
        Submit Application
      </button>
    </form>
    <p class="text-gray-600 text-sm text-center mt-4">Already applied? <a href="login.php" class="text-blue-600 hover:underline">Login here</a></p>
  </div>
</body>
</html>
