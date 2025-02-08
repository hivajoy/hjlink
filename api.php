<?php
// Include MongoDB library
require 'vendor/autoload.php'; // Make sure you have installed MongoDB PHP library using Composer

// MongoDB connection string
$uri = 'mongodb+srv://hivajoy:BATn3jrauiiB8OAL@cluster0.oro7m.mongodb.net/?retryWrites=true&w=majority&appName=Cluster0';
$client = new MongoDB\Client($uri);
$database = $client->selectDatabase('urlshortener');  // select database
$collection = $database->selectCollection('urls');   // select collection

// Generate short code (6 alphanumeric characters)
function generateShortCode() {
    return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 6);
}

if (isset($_GET['longurl']) && !empty($_GET['longurl'])) {
    $longUrl = $_GET['longurl'];
    
    // Generate a unique short code
    $shortCode = generateShortCode();
    
    // Prepare the data to insert into MongoDB
    $data = [
        'longurl' => $longUrl,
        'shortcode' => $shortCode,
    ];

    // Check if short code already exists (avoid duplicates)
    $existing = $collection->findOne(['longurl' => $longUrl]);
    if ($existing) {
        echo "URL already shortened: mydomain.com/" . $existing['shortcode'];
    } else {
        // Insert the long URL and short code into MongoDB
        $collection->insertOne($data);
        
        // Output the short URL
        echo "Short URL: mydomain.com/redirect.php?short_code=" . $shortCode;
    }
} else {
    echo "Please provide a long URL using the 'longurl' parameter.";
}
?>
