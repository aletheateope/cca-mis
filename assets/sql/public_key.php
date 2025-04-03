<?php
function generatePublicKey()
{
    return bin2hex(random_bytes(16)); // Generate a 32-character key
}
