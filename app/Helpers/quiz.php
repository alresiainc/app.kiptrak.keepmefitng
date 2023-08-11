<?php
class TheSolution {
    
    

    function solution($k) {
        //write here
        return -1;
    }
}

function readMatrix() {
    $k = array_map('intval', explode(' ', readline()));
    
    //return array_map(function($row){ return array_map('intval', explode(' ', $row)); }, explode(',', readline()));
}

// read but dont change the code below



// solution
$sol = new TheSolution();
$output = $sol->solution($k);

//print output
echo $output;

//////////////////////////////////////////////////////
class SolutionClass {
    
    function solution($k) {
        $res = -1;
        //write your solution here
        return $res;
    }
}

function readMatrix() {
    
    return array_map(function($row){ return array_map('intval', explode(' ', $row)); }, explode(',', readline()));
}

// read but dont change the code below
$mtx = readMatrix();
$solutionc = new SolutionClass();
$output = $solutionc->solution($mtx);

// print the output
echo $output;



