<?php
// process/contact.php
require_once '../config/database.php';

// Set content type to JSON
header('Content-Type: application/json');

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// Initialize response
$response = ['success' => false, 'message' => ''];

try {
    // Database connection
    $database = new Database();
    $conn = $database->connect();
    
    // Sanitize and validate input
    $nama = sanitize_input($_POST['nama'] ?? '');
    $email = sanitize_input($_POST['email'] ?? '');
    $telepon = sanitize_input($_POST['telepon'] ?? '');
    $subjek = sanitize_input($_POST['subjek'] ?? '');
    $pesan = sanitize_input($_POST['pesan'] ?? '');
    
    // Validation
    $errors = [];
    
    if (empty($nama)) {
        $errors[] = 'Nama lengkap harus diisi';
    }
    
    if (empty($email)) {
        $errors[] = 'Email harus diisi';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Format email tidak valid';
    }
    
    if (empty($pesan)) {
        $errors[] = 'Pesan harus diisi';
    }
    
    if (!empty($errors)) {
        $response['message'] = 'Validasi gagal: ' . implode(', ', $errors);
        echo json_encode($response);
        exit;
    }
    
    // Check for spam (simple rate limiting)
    $ip_address = $_SERVER['REMOTE_ADDR'];
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM kontak WHERE created_at > DATE_SUB(NOW(), INTERVAL 1 HOUR)");
    $stmt->execute();
    $recent_submissions = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    if ($recent_submissions > 10) {
        $response['message'] = 'Terlalu banyak pesan dalam satu jam terakhir. Silakan coba lagi nanti.';
        echo json_encode($response);
        exit;
    }
    
    // Insert into database
    $stmt = $conn->prepare("INSERT INTO kontak (nama, email, telepon, subjek, pesan) VALUES (?, ?, ?, ?, ?)");
    $result = $stmt->execute([$nama, $email, $telepon, $subjek, $pesan]);
    
    if ($result) {
        $response['success'] = true;
        $response['message'] = 'Terima kasih! Pesan Anda telah berhasil dikirim. Kami akan segera menghubungi Anda.';
        
        // Send notification email (optional)
        if (function_exists('mail')) {
            $to = 'rw7temas@gmail.com';
            $subject = 'Pesan Baru dari Website RW 7 - ' . $subjek;
            $message = "Pesan baru dari website RW 7:\n\n";
            $message .= "Nama: $nama\n";
            $message .= "Email: $email\n";
            $message .= "Telepon: $telepon\n";
            $message .= "Subjek: $subjek\n\n";
            $message .= "Pesan:\n$pesan\n\n";
            $message .= "Dikirim pada: " . date('Y-m-d H:i:s');
            
            $headers = "From: noreply@rw7temas.com\r\n";
            $headers .= "Reply-To: $email\r\n";
            $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
            
            mail($to, $subject, $message, $headers);
        }
        
    } else {
        $response['message'] = 'Terjadi kesalahan saat mengirim pesan. Silakan coba lagi.';
    }
    
} catch(PDOException $e) {
    error_log("Contact form error: " . $e->getMessage());
    $response['message'] = 'Terjadi kesalahan sistem. Silakan coba lagi nanti.';
} catch(Exception $e) {
    error_log("Contact form error: " . $e->getMessage());
    $response['message'] = 'Terjadi kesalahan. Silakan coba lagi.';
}

echo json_encode($response);
?>