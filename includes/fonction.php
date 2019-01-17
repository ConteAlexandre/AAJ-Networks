<?php

function debug($a){
    echo '<pre>';
    print_r($a);
    echo '</pre>';
}
$protocols = array();

function countprotocol($data,$protocols){

    $protocol = explode(':', $data['_source']['layers']['frame']['frame.protocols']); // découper frame protocol
    $protocol = end($protocol); //prendre dernière valeur du tableau (protocol)

    if (isset($protocols[$protocol])) { $protocols[$protocol] ++ ; } // incrémentation protocol
    else { $protocols[$protocol] = 1 ; } // sinon initialiser le nouveau protocol
    return $protocols;
}
function getIndex ($mask) {
    for ($index = 0; $index < count(explode('.', $mask)); $index++) {
        if (intval($mask[$index]) < 255) {
            return $index;
        }
    }
}
function getFirstIp($magicVal, $ip, $index) {
    $multipleBefore = 0;
    $ipIndex = ($ip[$index]);

    while ($multipleBefore < $ipIndex) {
        $multipleBefore += $magicVal;
    }
    $multipleBefore -= $magicVal;
    $firstIp = array();
    for ($i=0; $i< count(explode('.', $ip)); $i++) {
        if ($i < $index) {
            $firstIp[] = $ip[$i];
        } else if ($i === $index) {
            $firstIp[] = strval($multipleBefore);
        } else {
            $firstIp[] = "0";
        }
    }

    return $firstIp;
}
function getLastIP($magicVal, $ip, $index) {
    $multipleBefore = 0;
    $ipIndex = intval($ip[$index]);
    while ($multipleBefore < $ipIndex) {
        $multipleBefore += $magicVal;
    }
    $multipleBefore -= $magicVal;
    $lastIp = array();
    for ($i=0; $i< count(explode('.', $ip)); $i++) {
        if ($i < $index) {
            $lastIp[] = $ip[$i];
        } else if ($i === $index) {
            $lastIp[] = strval($multipleBefore);
        } else {
            $lastIp[] = "255";
        }
    }


    return $lastIp;
}
function compareIP($ipSrc, $firstIp, $lastIp) {

    for ($i = 0; $i < 4; $i++) {
        $fByte = intval($firstIp[$i]);
        $lByte = intval($lastIp[$i]);
        $srcByte = intval($ipSrc[$i]);

        if ($srcByte < $fByte || $srcByte > $lByte) {
            return false;
        }
    }

    return true;
}

function countip($data,$ips){

    $ip = explode(':', $data['_source']['layers']['ip']['ip.dst']); // découper frame protocol
    $ip = end($ip); //prendre dernière valeur du tableau (protocol)

    if (isset($ips[$ip])) {
        $ips[$ip]++;
    } // incrémentation protocol
    else {
        $ips[$ip] = 1;
    } // sinon initialiser le nouveau protocol
    return $ips;

}
