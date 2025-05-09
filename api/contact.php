<?php
// api/contact.php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// 1. Form verilerini al
$name = $_POST['name'] ?? null;
$email = $_POST['email'] ?? null;
$message = $_POST['message'] ?? null;

// 2. Boş alan kontrolü
if (!$name || !$email || !$message) {
    echo json_encode(["success" => false, "message" => "Eksik alanlar var."]);
    exit;
}

// 3. E-posta doğrulama
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(["success" => false, "message" => "Geçersiz e-posta adresi."]);
    exit;
}

// 4. Girişleri temizle
$name = htmlspecialchars(trim($name));
$email = htmlspecialchars(trim($email));
$message = htmlspecialchars(trim($message));

// 5. Veritabanına kaydet
try {
    $pdo = new PDO("mysql:host=localhost;dbname=webpro;charset=utf8", "kullanici_adi", "sifre");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("INSERT INTO iletisim (ad, email, mesaj) VALUES (?, ?, ?)");
    $stmt->execute([$name, $email, $message]);

    echo json_encode(["success" => true, "message" => "Mesaj başarıyla gönderildi."]);
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Veritabanı hatası: " . $e->getMessage()]);
}
