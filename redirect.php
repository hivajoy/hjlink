<?php
// Include MongoDB library
require 'vendor/autoload.php'; // Make sure you have installed MongoDB PHP library using Composer

// MongoDB connection string
$uri = 'mongodb+srv://hivajoy:BATn3jrauiiB8OAL@cluster0.oro7m.mongodb.net/?retryWrites=true&w=majority&appName=Cluster0';
$client = new MongoDB\Client($uri);
$database = $client->selectDatabase('urlshortener');  // select database
$collection = $database->selectCollection('urls');   // select collection

if (isset($_GET['short_code']) && !empty($_GET['short_code'])) {
    $shortCode = $_GET['short_code'];
    
    // Find the long URL based on the short code
    $urlData = $collection->findOne(['shortcode' => $shortCode]);
    
    if ($urlData) {
        // Redirect to the long URL
        header('Location: ' . $urlData['longurl']);
        exit();
    } else {
        echo "Short code not found!";
    }
} else {
    echo "No short code provided!";
}
?>
