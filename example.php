<?php
require 'src/CurlMulti.php';

$handles = [
    [
        CURLOPT_URL=>"http://example.com/",
        CURLOPT_HEADER=>false,
        CURLOPT_FOLLOWLOCATION=>false,
        CURLOPT_WRITEFUNCTION=>function($ch, $body)
        {
            print $body;
            return strlen($body);
        }
    ],
    [
        CURLOPT_URL=>"httpzzz://example.com/",
        CURLOPT_HEADER=>false,
        CURLOPT_FOLLOWLOCATION=>false,
        CURLOPT_WRITEFUNCTION=>function($ch, $body)
        {
            print $body;
            return strlen($body);
        }
    ],
    [
        CURLOPT_URL=>"http://www.php.net",
        CURLOPT_HEADER=>false,
        CURLOPT_FOLLOWLOCATION=>false,

        // this function is called by curl for each header received
        // This complies with RFC822 and RFC2616, please do not suggest edits to make use of the mb_ string functions, it is incorrect!
        // https://stackoverflow.com/a/41135574
        CURLOPT_HEADERFUNCTION=>function($ch, $header)
        {
            print "header from http://www.php.net: ".$header;
            return strlen($header);
        },
        CURLOPT_WRITEFUNCTION=>function($ch, $body)
        {
            print $body;
            return strlen($body);
        }
    ]
];



//create the multiple cURL handle
$CurlMulti = new TH\CurlMulti\CurlMulti();

foreach($handles as $opts) {
    // create cURL resources
    $ch = curl_init();

    // set URL and other appropriate options
    curl_setopt_array($ch, $opts);

    // add the handle
    $CurlMulti->add($ch, function($ch, $statusCode) {
        $info = curl_getinfo($ch);
    
        if ($statusCode !== CURLE_OK) {
            // TODO: handle the error
            print "Curl handle error: ".curl_strerror($statusCode)." for ".$info['url'].PHP_EOL;
            return;
        }
    
        print_r($info);
        $body = curl_multi_getcontent($ch);
        echo $body;
    
    });
}

$statusCode = $CurlMulti->run();
if ($statusCode !== CURLM_OK) {
    print "Curl multi handle error: ".curl_multi_strerror($statusCode)." for ".$info['url'].PHP_EOL;
}

    
    
    
    