<?php
function isPangram($text) {

    $cleanedText = strtolower(preg_replace("/[^a-z]/", "", $text));

    foreach (range('a', 'z') as $letter) {
        if (strpos($cleanedText, $letter) === false) {
            return false;
        }
    }
    return true;
}

$text = "The quick brown fox jumps over the lazy dog.";
if (isPangram($text)) {
    echo "true\n";
} else {
    echo "false\n";
}
?>
