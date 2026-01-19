<?php
date_default_timezone_set("Asia/Jakarta");
include "connect.php"; // koneksi DB

$BOT_TOKEN = "8560333706:AAGxE_BSOgMsH9fBPlfRojempGWv8Ounro0";
$CHAT_ID  = "1876930467";

$COOLDOWN = 300; // 5 menit
$LOG_DIR = __DIR__ . "/alert_log";

// pastiin folder ada
if (!file_exists($LOG_DIR)) {
    mkdir($LOG_DIR, 0777, true);
}

// data dari ESP32
$id_rack = $_POST['id_rack'] ?? null;
$current = $_POST['current'] ?? null;
$power   = $_POST['power'] ?? null;

if (!$id_rack || !$current) {
    http_response_code(400);
    exit("Data ga lengkap");
}

// ambil threshold dari DB
$q = mysqli_query($conn, " SELECT 
        t.number_treshold,
        nr.company,
        nr.description
    FROM treshold t
    JOIN number_rack nr ON t.id_rack = nr.id_rack
    WHERE t.id_rack = '$id_rack'
    ORDER BY t.edited DESC
    LIMIT 1"
);


if (!$q) {
    exit("Query error: " . mysqli_error($conn));
}

if (mysqli_num_rows($q) == 0) {
    exit("Threshold rack ga ditemukan");
}

$data = mysqli_fetch_assoc($q);

$threshold = (float) $data['number_treshold'];
$company     = $data['company'];
$description = $data['description'];


//  cek lewat threshold
if ($current > $threshold) {

    $logFile = "$LOG_DIR/rack_$id_rack.txt";
    $now = time();

    if (file_exists($logFile)) {
        $last = (int) file_get_contents($logFile);
        if (($now - $last) < $COOLDOWN) {
            exit("Cooldown aktif");
        }
    }

    $time = date("Y-m-d H:i:s");

    $time = date("Y-m-d H:i:s");

$msg =
"🚨🚨 *CRITICAL POWER ALERT* 🚨🚨\n\n" .
"🏢 *{$company}*\n" .
"📦 {$description}\n\n" .
"🆔 *Rack ID*        : {$id_rack}\n" .
"⚡ *Arus Saat Ini*  : {$current} A\n" .
"🚫 *Threshold*    : {$threshold} A\n" .
"🔌 *Daya Total*    : {$power} W\n\n" .
"📊 *Status*:\n" .
"❌ *Melebihi Threshold*\n\n" .
"⚠️ *Tindakan*:\n" .
"Segera cek beban server dan pendinginan.\n" .
"Risiko downtime & kerusakan perangkat.\n\n" .
"🕒 *Waktu*:\n{$time}";

    sendTelegram($BOT_TOKEN, $CHAT_ID, $msg);
    file_put_contents($logFile, $now);
}

echo "OK";

// ======================
function sendTelegram($token, $chat_id, $message) {
    $url = "https://api.telegram.org/bot{$token}/sendMessage";

    $data = [
        'chat_id' => $chat_id,
        'text' => $message,
        'parse_mode' => 'Markdown'
    ];

    $opt = [
        "http" => [
            "header"  => "Content-Type: application/x-www-form-urlencoded",
            "method"  => "POST",
            "content" => http_build_query($data),
            "timeout" => 5
        ]
    ];

    file_get_contents($url, false, stream_context_create($opt));
}
