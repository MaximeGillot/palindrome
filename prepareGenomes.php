<?php


function fromFastaFileIntoArray($path) 
{
	$file = file_get_contents($path);
	$array = explode(">", $file);
	array_shift($array);
	return $array;
}

function fromArrayToFiles($path)
{/*
	$file = file_get_contents($path);
	$array = explode(">", $file);
	array_shift($array);
	echo count($array);
	for ($i=0; $i < count($array) ; $i++) {  
		$arrayTmp = explode(" ", $array[$i]);
		echo $arrayTmp[1];
		echo "\n";
		$file = fopen("allGenomes/".$arrayTmp[1].".fasta", "a+") ;
		fputs($file,">".$array[$i]);
	}*/
	
	$file ="";
	$handle = fopen($path , "r") or die("Couldn't get handle");
	if ($handle) {
    while (!feof($handle)) {
        $buffer = fgets($handle, 4096);
        if (strpos($buffer , ">") !== FALSE) 
        {
        	$arrayTmp = explode(" ", $buffer);
        	$file = fopen("allGenomes/".$arrayTmp[1].".fasta", "a+") ;
        }
        fputs($file,$buffer);
    }
    fclose($handle);
}

}

fromArrayToFiles($argv[1]);

?>