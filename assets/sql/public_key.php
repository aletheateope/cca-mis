<?php
// function generatePublicKey($length = 32)
// {
//     $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ-_.';
//     $charactersLength = strlen($characters);
//     $randomString = '';

//     for ($i = 0; $i < $length; $i++) {
//         $index = random_int(0, $charactersLength - 1);
//         $randomString .= $characters[$index];
//     }

//     return $randomString;
// }

// function generatePrivateKey()
// {
//     return bin2hex(random_bytes(16)); // Generate a 32-character key
// }

function generatePublicKey()
{
    return bin2hex(random_bytes(16)); // Generate a 32-character key
}
