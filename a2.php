<?php

// Replace 'YOUR_BOT_TOKEN' with your actual Telegram bot token
$botToken = '6163881554:AAFg-PjrEpDht6Hq0lfPNOLja4utgoa5hk0';

// Replace 'YOUR_CHANNEL_ID' with the ID of your Telegram channel
$channelId = '871406443';

// Get the array of file URLs from the front end (you can receive this from a form or any other method)
$fileUrls = $_POST['file_urls'];

// Function to upload a file to Telegram
function uploadToTelegram($filePath, $botToken, $channelId) {
    $apiUrl = "https://api.telegram.org/bot{$botToken}/sendDocument";
    $postFields = [
        'chat_id' => $channelId,
        'document' => new CURLFile($filePath),
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        echo 'Error uploading file to Telegram: ' . curl_error($ch);
    } else {
        echo 'File uploaded successfully to Telegram!';
    }

    // Close cURL handle
    curl_close($ch);

    // Delete the temporary file
    unlink($filePath);
}

// Loop through each URL and process the file
foreach ($fileUrls as $url) {
    // Download the file content
    $fileContent = file_get_contents($url);

    // Save the file to a temporary folder
    $tempFilePath = tempnam(sys_get_temp_dir(), 'tempfile');
    file_put_contents($tempFilePath, $fileContent);

    // Upload the file to Telegram
    uploadToTelegram($tempFilePath, $botToken, $channelId);
}

?>
