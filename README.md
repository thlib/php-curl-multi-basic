# php curl multi basic example
Basic implementation of PHP curl multi

There are many implementations of curl multi, but the majority suffer from high CPU usage, inefficient memory management and run only after each request has finished instead of immediately.

Here is an example of how to avoid all of those issues without overloading it with functionality.

```
require 'CurlMulti.php';

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
$CurlMulti = new CurlMulti();

foreach($handles as $opts) {
    // create cURL resources
    $ch = curl_init();

    // set URL and other appropriate options
    curl_setopt_array($ch, $opts);

    // add the handle
    $CurlMulti->add($ch);
}

$statusCode = $CurlMulti->run(function($ch, $statusCode) {
    $info = curl_getinfo($ch);

    if ($statusCode !== CURLE_OK) {
        // handle the error somehow
        print "Curl handle error: ".curl_strerror($statusCode)." for ".$info['url'].PHP_EOL;
        return;
    }

    //$body = curl_multi_getcontent($ch);
    //print $body;
});
if ($statusCode !== CURLM_OK) {
    print "Curl multi handle error: ".curl_multi_strerror($statusCode)." for ".$info['url'].PHP_EOL;
}
```
