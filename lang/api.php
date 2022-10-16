<?php
    $apiKey = '<API Key>';
    
    $url = 'https://www.googleapis.com/language/translate/v2?key=' . $apiKey . '&q=' . rawurlencode($text) . '&source=en&target=fr';

    $handle = curl_init($url);
    curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($handle);                 
    $responseDecoded = json_decode($response, true);
    curl_close($handle);

    echo 'Source: ' . $text . '<br>';
    echo 'Translation: ' . $responseDecoded['data']['translations'][0]['translatedText'];
?>



#The user has to purchase the key or use translation API key availible online. 
