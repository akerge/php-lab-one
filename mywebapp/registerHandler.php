<?php

$postedData = $_POST['data'];

$email = $postedData['email'];
$firstname = $postedData['firstname'];
$lastname = $postedData['lastname'];


$serializedData = serialize($postedData);

// write
$handle = fopen(__DIR__ . '/data.dat', 'w');
fwrite($handle, $serializedData);
fclose($handle);

// get the file content
$string = file_get_contents(__DIR__ . '/data.dat');
$data = unserialize($string); // unserialize it
$output = '';
foreach ($data as $key => $value) {
    $field = ucwords($key); // make first letter of each word uppercase
    $output .= "$field: $value" . '<br>';
}

echo $output;