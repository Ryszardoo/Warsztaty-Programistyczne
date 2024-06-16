<?php
function digitSum($number) {
    function sumOfDigits($number) {
        $sum = 0;
        while ($number > 0) {
            $sum += $number % 10;
            $number = floor($number / 10);
        }
        return $sum;
    }

    while ($number >= 10) {
        $number = sumOfDigits($number);
    }

    return $number;
}

$number = 987654;
$result = digitSum($number);
echo "Suma cyfr liczby aż do uzyskania wartości mniejszej niż 10: " . $result . "\n";
?>
