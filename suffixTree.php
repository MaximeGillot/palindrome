<?php

class node {
    public $present;
    public $filsA;
    public $filsC;
    public $filsG;
    public $filsT;
    public $filsN;

    function  __construct() 
    {
    	$this->present = 0 ;
    }


	###################################################################
	# inser @string dans l'arbre des suffixes
	# @value 
	###################################################################
    function makeFils($string) {
    	if (strlen($string) == 0 ) 
    	{
    		if ($this->present == 0) 
    		{
    			$this->present++;
    		}
    		
    	}
    	else
    	{
	    	switch ($string[0])
	        {
	            case 'A':
	                if (isset($this->filsA)) 
	                {
	                    $this->filsA->makeFils(substr($string,1));
	                }
	                else
	                {
	                    $this->filsA = new node() ;
	                    $this->filsA->makeFils(substr($string,1));
	                }
	                break;
	            
	            case 'C':
	                if (isset($this->filsC)) 
	                {
	                    $this->filsC->makeFils(substr($string,1));
	                }
	                else
	                {
	                    $this->filsC = new node() ;
	                    $this->filsC->makeFils(substr($string,1));
	                }
	                break;

	            case 'G':
	                if (isset($this->filsG)) 
	                {
	                    $this->filsG->makeFils(substr($string,1));
	                }
	                else
	                {
	                    $this->filsG = new node() ;
	                    $this->filsG->makeFils(substr($string,1));
	                }
	                break;

	            case 'T':
	                if (isset($this->filsT)) 
	                {
	                    $this->filsT->makeFils(substr($string,1));
	                }
	                else
	                {
	                    $this->filsT = new node() ;
	                    $this->filsT->makeFils(substr($string,1));
	                }
	                break;

	            case 'N':
	                if (isset($this->filsN)) 
	                {
	                    $this->filsN->makeFils(substr($string,1));
	                }
	                else
	                {
	                    $this->filsN = new node() ;
	                    $this->filsN->makeFils(substr($string,1));
	                }
	                break;                	
                	default:
                		echo "lettre inconnu trouvé";
                		echo $string[0];
                		echo "\n";
                	break;
	                
	        }
	    }

    } #fin de methode makeFils

	###################################################################
	# determiner si un suffixe est present dans l'arbre
	# @string chaine a tester
	# @return true si la chaine est présente dans l'arbre des suffixes , faux sinon
	###################################################################
    function isInTree($string) {
        if (strlen($string) == 0) {
            return true ;
        }
        else
        {
             switch ($string[0])
            {
                case 'A':
                    if (isset($this->filsA)) 
                    {
                       if( $this->filsA->isInTree(substr($string,1)) )
                       {
                        return true ;
                       }
                    }
                    else
                    {
                        return false ;
                    }
                    break;
                
                case 'C':
                    if (isset($this->filsC)) 
                    {
                        if ( $this->filsC->isInTree(substr($string,1)) )
                        {
                            return true ;
                        }
                    }
                    else
                    {
                        return false ;
                    }
                    break;

                case 'G':
                    if (isset($this->filsG)) 
                    {
                       if( $this->filsG->isInTree(substr($string,1)) )
                       {
                        return true ;
                       }
                    }
                    else
                    {
                        return false ;
                    }
                    break;

                case 'T':
                    if (isset($this->filsT)) 
                    {
                       if( $this->filsT->isInTree(substr($string,1)) )
                       {
                        return true ;
                       }
                    }
                    else
                    {
                       return false ;
                    }
                    break;

                case 'N':
                    if (isset($this->filsN)) 
                    {
                       if( $this->filsN->isInTree(substr($string,1)) )
                       {
                        return true ;
                       }
                    }
                    else
                    {
                       return false ;
                    }
                    break;

                 	
                 	default:
                 		return false;
                 		break;
                 
                }
            }
    } #fin de methode isInTree
		

    
	###################################################################
	# determiner le plus grand palindrome présent dans l'arbre
	# @current mettre a vide
	# @best un string vide a l'origine qui devienra le plus grand palindrome
	# @return rien
	# @exemple :
	# $best = "";
	# $arbre-> longestSuffix("",$best);
	# echo "le plus grand palindrome est : $best "; 
	###################################################################
    function longestSuffix($current , &$best ) {

        $sizelongest = strlen($best);

        if (isset($this->filsA)) 
        {
            if (strlen($current)+1 > $sizelongest ) {
                $best = $current."A";
                $sizelongest++;
            }
            $this->filsA->longestSuffix($current."A" , $best );
        }

        if (isset($this->filsC)) 
        {
            if (strlen($current)+1 > $sizelongest ) {
                $best = $current."C";
                $sizelongest++;
            }
            $this->filsC->longestSuffix($current."C" , $best );
        }

        if (isset($this->filsG)) 
        {
            if (strlen($current)+1 > $sizelongest ) {
                $best = $current."G";
                $sizelongest++;
            }
            $this->filsG->longestSuffix($current."G" , $best );
        }

        if (isset($this->filsT)) 
        {
            if (strlen($current)+1 > $sizelongest ) {
                $best = $current."T";
                $sizelongest++;
            }
            $this->filsT->longestSuffix($current."T" , $best );
        }

        if (isset($this->filsN)) 
        {
            if (strlen($current)+1 > $sizelongest ) {
                $best = $current."N";
                $sizelongest++;
            }
            $this->filsN->longestSuffix($current."N" , $best );
        }
        
    } #fin de methode longestSuffix

    	###################################################################
	# fusionne l'arbre des suffixes this avec l'arbre @tree
	# @tree un arbre des suffixes
	# @return rien
	###################################################################
    function addTree($tree) {

    	if ($tree->present > 0) 
    	{
    		$this->present++;
    	}
             
        if (isset($tree->filsA)) 
        {
        	if (!isset($this->filsA)) 
        	{
        		$newNode = new node();
        		$this->filsA = $newNode;
        	}
        	$this->filsA->addTree($tree->filsA);
        }

        if (isset($tree->filsC)) 
        {
        	if (!isset($this->filsC)) 
        	{
        		$newNode = new node();
        		$this->filsC = $newNode;
        	}
        	$this->filsC->addTree($tree->filsC);
        }

        if (isset($tree->filsG)) 
        {
        	if (!isset($this->filsG)) 
        	{
        		$newNode = new node();
        		$this->filsG = $newNode;
        	}
        	$this->filsG->addTree($tree->filsG);
        }

        if (isset($tree->filsT)) 
        {
        	if (!isset($this->filsT)) 
        	{
        		$newNode = new node();
        		$this->filsT = $newNode;
        	}
        	$this->filsT->addTree($tree->filsT);
        }

        if (isset($tree->filsN)) 
        {
        	if (!isset($this->filsN)) 
        	{
        		$newNode = new node();
        		$this->filsN = $newNode;
        	}
        	$this->filsN->addTree($tree->filsN);
        }

    } #fin de methode addTree
}




?>