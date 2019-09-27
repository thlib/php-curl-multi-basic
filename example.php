<?php
require 'CurlMulti.php';

$handles = [
    [
        CURLOPT_URL=>"http://example.com/",
        CURLOPT_HEADER=>false,
        CURLOPT_RETURNTRANSFER=>true,
        CURLOPT_FOLLOWLOCATION=>false,
    ],
    [
        CURLOPT_URL=>"http://www.php.net",
        CURLOPT_HEADER=>false,
        CURLOPT_RETURNTRANSFER=>true,
        CURLOPT_FOLLOWLOCATION=>false,

        // this function is called by curl for each header received
        // This complies with RFC822 and RFC2616, please do not suggest edits to make use of the mb_ string functions, it is incorrect!
        // https://stackoverflow.com/a/41135574
        CURLOPT_HEADERFUNCTION=>function($ch, $header)
        {
            print "header from http://www.php.net: ".$header;
            //$header = explode(':', $header, 2);
            //if (count($header) < 2){ // ignore invalid headers
            //    return $len;
            //}

            //$headers[strtolower(trim($header[0]))][] = trim($header[1]);

            return strlen($header);
        }
    ]
];
    
//create the multiple cURL handle
$CurlMulti = new CurlMulti();

foreach($handles as $opts) {
    // create cURL resources
    $ch = curl_init();

    // set URL and other appropriate options
    curl_setopt_array($ch, $opts);

    // add the handle
    $CurlMulti->add($ch);
}

$count = $CurlMulti->run(function($ch, $status){
    $info = curl_getinfo($ch);

    if($status !== CURLE_OK){
        // handle the error somehow
        print "Error: ".$info['url'].PHP_EOL;
    }

    if($status === CURLE_OK){
        print_r($info);
        $body = curl_multi_getcontent($ch);
        print $body;
    }

});

print $count.PHP_EOL;
	
	
	
	