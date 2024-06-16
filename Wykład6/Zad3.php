<?php
function multiplyMatrixes($matrix1, $matrix2)
{
    $row1 = count($matrix1);
    $column1 = count($matrix1[0]);
    $row2 = count($matrix2);
    $column2 = count($matrix2[0]);

    if ($column1 != $row2) {
        echo "Wymiary macierzy nie są zgodne do mnożenia.\n";
        return null;
    }

    $result = array();
    for ($i = 0; $i < $row1; $i++) {
        $result[$i] = array();
        for ($j = 0; $j < $column2; $j++) {
            $result[$i][$j] = 0;
        }
    }

    for ($i = 0; $i < $row1; $i++) {
        for ($j = 0; $j < $column2; $j++) {
            for ($k = 0; $k < $column1; $k++) {
                $result[$i][$j] += $matrix1[$i][$k] * $matrix2[$k][$j];
            }
        }
    }

    return $result;
}

$matrix1 = [
    [1, 2],
    [3, 4]
];

$matrix2 = [
    [5, 6],
    [7, 8]
];

$result = multiplyMatrixes($matrix1, $matrix2);

if ($result !== null) {
    foreach ($result as $row) {
        echo implode(' ', $row) . "\n";
    }
}
?>
