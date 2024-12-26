<?php
function iPharse (){
    $seed = 12;
    $dateNow = date('Y-m-d');
    return sha256($seed + $dateNow);
}
function isPharse ($pharse){
    if ($pharse == iPharse()) {
        return true;
    }else{
        return false;
    }
}