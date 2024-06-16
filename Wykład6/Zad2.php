

<?php

function sumSequence($a, $q, $n)
{

    $sum = (((2*$a) + (($n -1) * $q))/2) * $n;
    echo "Suma wyrazów ciągu arytmetycznego: $sum";

    if ($n == 1) {
        $sum = $a * $n;
    }
    else {
        $sum = $a * ((1 - pow($q, $n)) / (1 - $q));
    }

    echo "\nSuma wyrazów ciągu geometrycznego: $sum";


}

sumSequence(2,3,5);
?>
