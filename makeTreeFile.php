<?php
include 'palindrome.php' ;

makeAllPalFile($argv[1]);
$node = new node();
$node = makeCommonSuffixTreeFromFile($argv[1]);
$best = "";
$node->longestSuffix("",$best);
echo $best ;
?>