<?php

function findFirst($start, $end)
{
    function ifFirst($num)
    {
        if($num < 2)
        {
            return false;
        }
        for ($i = 2; $i <= sqrt($num); $i++) {
            if ($num % $i == 0) {
                return false;
            }
        }
        return true;
    }

    for ($i = $start; $i <= $end; $i++) {
        if (ifFirst($i)) {
            echo $i . " ";
        }
    }
}

?>


<?php
findFirst(1,100);
?>