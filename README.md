# php curl multi basic example
Basic implementation of PHP curl multi

There are many implementations of curl multi, but the majority suffer from high CPU usage, inefficient memory management and run only after each request has finished instead of immediately.

Here is an example of how to avoid all of those issues without overloading it with functionality.

```
$handles = [
    [
        CURLOPT_URL=>"http://example.com/",
        CURLOPT_RETURNTRANSFER=>true,
        CURLOPT_FOLLOWLOCATION=>false,
    ],
    [
        CURLOPT_URL=>"http://www.php.net",
        CURLOPT_RETURNTRANSFER=>true,
        CURLOPT_FOLLOWLOCATION=>false,
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

$CurlMulti->run(function($ch, $status){
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

```
