<?php


function fromFastaFileIntoArray($path) 
{
	$file = file_get_contents($path);
	$array = explode(">", $file);
	array_shift($array);
	return $array;
}

function fromArrayToFiles($path)
{
	
    	$file ="";
    	$handle = fopen($path , "r") or die("Couldn't get handle");
    	if ($handle) 
        {
        while (!feof($handle)) 
            {
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

function fromAllGenomeToPalindromeFile($path)
{
    
    $file ="";
    $filleAllGenomes = fopen($path , "r") or die("File not found");
    if ($filleAllGenomes) 
    {
        while (!feof($filleAllGenomes)) 
        {
            $buffer = fgets($filleAllGenomes, 4096);
            if (strpos($buffer , ">") !== FALSE) 
            {
                $arrayTmp = explode(" ", $buffer);
                $arrayTmp2 = explode(">", $arrayTmp[0]);                
                $nameFile = $arrayTmp[1];
                for ($i=2; $i < count($arrayTmp)-1 ; $i++) 
                { 
                    $nameFile .= " ".$arrayTmp[$i];
                }
                $nameFile = $nameFile." ".$arrayTmp2[1].".fasta" ;
                echo $nameFile;
                echo "\n";
                $nameFile = mb_ereg_replace("([^\w\s\d\-_~,;\[\]\(\).])", '', $nameFile);
                $file = fopen("allGenomes/".$nameFile, "a+") ;
            }
            fputs($file,$buffer);
            if($file === false)
            {
                echo "probleme false ;";
            }
        }

        fclose($filleAllGenomes);
    }

}

fromAllGenomeToPalindromeFile($argv[1]);


?>