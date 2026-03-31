<?php


function isPalindrome($string)
{
    $clean = strtolower(str_replace('', ' ', $string));

    return $clean === strrev($clean);
}

var_dump(isPalindrome('katak'));
var_dump(isPalindrome('majikan'));
var_dump(isPalindrome('ada'));




