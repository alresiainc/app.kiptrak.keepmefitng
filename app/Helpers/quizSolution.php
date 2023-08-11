<?php
class TheSolution {
    
    function solution($k) {
        sort($k); // Sort the cards in ascending order.
        $maxSum = 0;
        
        // Pair up the cards and calculate the sum of the smaller values in each pair.
        for ($i = 0; $i < count($k); $i += 2) {
            $maxSum += $k[$i];
        }
        
        return $maxSum;
    }
}

function readMatrix() {
    return array_map('intval', explode(' ', readline()));
}

// read but don't change the code below
$k = readMatrix();

// solution
$sol = new TheSolution();
$output = $sol->solution($k);

// print output
echo $output;
