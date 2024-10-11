<?php
session_start();

// Daftar IP dan Lokasi
$ip_list = [
    "google" => "8.8.8.8",
    "google2" => "8.8.4.4",
    "1.1.1.1" => "1.1.1.1"
];

// Tambah IP baru melalui form
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST["new_ip"]) && !empty($_POST["location"])) {
    $new_ip = $_POST["new_ip"];
    $location = $_POST["location"];

    // Simpan IP baru ke session
    if (filter_var($new_ip, FILTER_VALIDATE_IP)) {
        $_SESSION['extra_ips'][$location] = $new_ip;
    } else {
        echo "<p class='text-red-500 text-center'>Invalid IP Address</p>";
    }
}

// Gabungkan IP default dengan IP yang disimpan di session
if (isset($_SESSION['extra_ips'])) {
    $ip_list = array_merge($ip_list, $_SESSION['extra_ips']);
}

// Token Bot Telegram dan Chat ID (masukkan token dan chat ID kamu)
$telegram_token = 'YOUR_TELEGRAM_BOT_TOKEN';
$telegram_chat_id = 'YOUR_CHAT_ID';

// Fungsi untuk mengecek status IP menggunakan curl via HTTPS
function check_ip_status($ip, $location) {
    $url = "https://$ip"; 
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10); 
    curl_setopt($ch, CURLOPT_HEADER, true); 
    curl_setopt($ch, CURLOPT_NOBODY, true); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); 
    curl_setopt($ch, CURLOPT_TIMEOUT, 10); // Set total timeout

    $response = curl_exec($ch);
    $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE); 
    $error = curl_error($ch);
    curl_close($ch);

    if ($status_code != 200) {
        // Jika status Down, kirim pesan ke Telegram
        send_telegram_notification($ip, $location);
        return "Down";
    } else {
        return "Up";
    }
}

// Fungsi untuk mengirimkan pesan ke Telegram
function send_telegram_notification($ip, $location) {
    global $telegram_token, $telegram_chat_id;

    $timestamp = date("Y-m-d H:i:s");
    $message = "⚠️ *ALERT: SDWAN IP DOWN*\n\n"
             . "*Location:* $location\n"
             . "*IP Address:* $ip\n"
             . "*Timestamp:* $timestamp\n"
             . "Please check immediately!";

    $url = "https://api.telegram.org/bot$telegram_token/sendMessage";
    $data = [
        'chat_id' => $telegram_chat_id,
        'text' => $message,
        'parse_mode' => 'Markdown'
    ];

    $options = [
        'http' => [
            'method' => 'POST',
            'header' => "Content-Type:application/x-www-form-urlencoded\r\n",
            'content' => http_build_query($data)
        ]
    ];

    $context = stream_context_create($options);
    file_get_contents($url, false, $context);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PILAR (Pantauan IP Laporan Real-Time)</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        let autoRefresh;

        function startRefresh() {
            if (!autoRefresh) {
                autoRefresh = setInterval(function() {
                    window.location.reload();
                }, 5000);
            }
        }

        function stopRefresh() {
            clearInterval(autoRefresh);
            autoRefresh = null;
        }
    </script>
</head>
<body class="bg-gray-100">

<div class="container mx-auto p-8">
    <h1 class="text-4xl font-bold mb-8 text-center text-gray-800">PILAR (Pantauan IP Laporan Real-Time)</h1>

    <!-- Tombol untuk Start/Stop Auto Refresh -->
    <div class="text-center mb-6">
        <button onclick="startRefresh()" class="bg-green-500 text-white font-bold py-2 px-4 rounded hover:bg-green-600">Start Auto Refresh</button>
        <button onclick="stopRefresh()" class="bg-red-500 text-white font-bold py-2 px-4 rounded hover:bg-red-600">Stop Auto Refresh</button>
    </div>

    <!-- Form untuk menambahkan IP baru -->
    <div class="mb-8">
        <form method="POST" class="flex justify-center items-center space-x-4">
            <input type="text" name="location" placeholder="Location Name" class="border p-2 rounded w-1/3" required>
            <input type="text" name="new_ip" placeholder="New IP Address" class="border p-2 rounded w-1/3" required>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Add IP</button>
        </form>
    </div>

    <!-- Tabel Hasil Pengecekan -->
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white shadow-md rounded-lg">
            <thead>
                <tr class="bg-gray-800 text-white">
                    <th class="py-3 px-6 text-left">Location</th>
                    <th class="py-3 px-6 text-left">IP Address</th>
                    <th class="py-3 px-6 text-center">Status</th>
                </tr>
            </thead>
            <tbody>

            <?php
            // Looping untuk setiap IP dan menampilkan hasilnya
            foreach ($ip_list as $location => $ip) {
                $status = check_ip_status($ip, $location);
                $status_color = ($status === "Up") ? "bg-green-500" : "bg-red-500";
                echo "<tr class='border-b border-gray-200 hover:bg-gray-100'>
                        <td class='py-3 px-6 text-left'>$location</td>
                        <td class='py-3 px-6 text-left'>$ip</td>
                        <td class='py-3 px-6 text-center'>
                            <span class='inline-block px-3 py-1 text-white rounded-full $status_color'>$status</span>
                        </td>
                      </tr>";
            }
            ?>

            </tbody>
        </table>
    </div>
</div>

</body>
</html>
