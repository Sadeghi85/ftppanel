#!/usr/bin/php
<?php

shell_exec('curl http://localhost:5000/uploadscript?file=' . urlencode($argv[1]));

// $ftpHome = '/var/www/FtpPanel/cdn1';
// $cdnDomain = 'cdn1.iribtv.ir';

// $dir = str_replace($ftpHome, '', $argv[1]);

// file_put_contents($argv[1].'.txt', 'http://'.$cdnDomain.encodeURI($dir));

// function encodeURI($url) {
    // # http://php.net/manual/en/function.rawurlencode.php
    // # https://developer.mozilla.org/en/JavaScript/Reference/Global_Objects/encodeURI
    // $unescaped = array(
        // '%2D'=>'-','%5F'=>'_','%2E'=>'.','%21'=>'!', '%7E'=>'~',
        // '%2A'=>'*', '%27'=>"'", '%28'=>'(', '%29'=>')'
    // );
    // $reserved = array(
        // '%3B'=>';','%2C'=>',','%2F'=>'/','%3F'=>'?','%3A'=>':',
        // '%40'=>'@','%26'=>'&','%3D'=>'=','%2B'=>'+','%24'=>'$'
    // );
    // $score = array(
        // '%23'=>'#'
    // );
    // return strtr(rawurlencode($url), array_merge($reserved,$unescaped,$score));

// }
