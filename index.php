<?php

require_once __DIR__ . "/core/UI.php";
require_once __DIR__ . "/core/Data.php";


use LennisDev\webDocs\UI;
use LennisDev\webDocs\Data;

$requestURL = $_SERVER["REQUEST_URI"];
try {
    $mimeType = Data::getMimeType($requestURL);
} catch (\Exception $e) {
    $mimeType = "text/plain";
}

if (str_starts_with($mimeType, "image/")) {
    header("Content-Type: " . $mimeType);
    Data::readfile($requestURL);
    exit;
} else if ($requestURL === "" || $requestURL === "/") {
    // THE INDEX PAGE
    echo "Welcome to webDocs!";
    exit;
}

try {
    UI::renderDoc($requestURL);
} catch (\Exception $e) {
    echo "Internal Error: " . $e->getMessage();
}
