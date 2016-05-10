<?php

    /*
     * Jose A. Gonzalez.
     * lintParserLib.php
     * This function's main functionality is to read the text passed as parameter
     * and convert any url not formatted as link into a link.
     */
     
    // Creates an auxilitar array containing the positions of the characters
    // within the pattern to speed up the search. #
    // Refer to http://en.wikipedia.org/wiki/Knuth%E2%80%93Morris%E2%80%93Pratt_algorithm
    // for more info.
    function createAuxTable($pattern) {

        $t = array(-1, 0);
        $i = 2;
        $j = 0;

        while ($i < strlen($pattern)) {
            if ($pattern[$i - 1] == $pattern[$j]) {
                $t[$i] = $j + 1;
                $j++;
                $i++;
            } else if ($j > 0) {
                $j = $t[$j];
            } else {
                $t[$i] = 0;
                $i++;
                $j = 0;
            }
        }
        return $t;
    }

    // Implementation of the Knuth-Morris-Pratt algorithm for sequential search
    // Refer to http://en.wikipedia.org/wiki/Knuth%E2%80%93Morris%E2%80%93Pratt_algorithm
    // for more info.
    function findPattern($pattern, $text, $iStart) {

        $i = 0;
        $o = $iStart;
        
        $t = createAuxTable($pattern);
        
        while ((($o + $i) < strlen($text)) && ($i < strlen($pattern))) {
            if ($text[$o + $i] == $pattern[$i]) {
                $i++;
            } else {
                $o += $i - $t[$i];
                if ($i > 0) {
                    $i = $t[$i];
                }
                $i++;
            }
        }

        if ($i == strlen($pattern)) {
            return $o;
        } else {
            return -1;
        }
    }
    
    // Function that controls the process of converting any non formatted link
    // into a well formatted link
    function linkParser ($text) {
        
        // variables initialisation.
        $iPos = 0;
        $lengthMatch = 0;
        $lengthMatchTemp = 0;

        $match = "";
        $output = "";

        $pHTTP = "http://";
        $pA = "</a>";
        $pHref = 'href="';
        $pTemp = $pHTTP;

        do {

            // Look for the pattern in the text
            $iPos = findPattern($pTemp, $text, $iPos);
            
            // If it finds it
            if ($iPos >= 0) {
                
                // checks for a previous href to confirm if is a well or not formatted link
                if ($pHref == substr($text, $iPos - strlen($pHref), strlen($pHref))) { 
                    // href found, look for the end of the link (</a>)
                    $pTemp = $pA;
                    $lengthMatch = strlen($pHref);
                } elseif ($pTemp == $pA) { 
                    // change the pattern to look for the next candidate link
                    $pTemp = $pHTTP;
                    $lengthMatch = strlen($pA);
                } else { 
                    // creates the new link
                    $lengthMatch = max(strpos($text, ". ", $iPos) - $iPos, $iPos);                    
                    
                    $lengthMatchTemp = (strpos($text, "/\r", $iPos) - $iPos);
                    if ($lengthMatchTemp > 0) {
                        $lengthMatch = ($lengthMatchTemp < $lengthMatch)? $lengthMatchTemp: $lengthMatch;                                                            
                    }
                    
                    $lengthMatchTemp = (strpos($text, "/ ", $iPos) - $iPos);
                    if ($lengthMatchTemp > 0) {
                        $lengthMatch = ($lengthMatchTemp < $lengthMatch)? $lengthMatchTemp: $lengthMatch;                                                            
                    } 
                    
                    $lengthMatchTemp = (strpos($text, " ", $iPos) - $iPos);
                    if ($lengthMatchTemp > 0) {
                        $lengthMatch = ($lengthMatchTemp < $lengthMatch)? $lengthMatchTemp: $lengthMatch;                                                            
                    }
                    
                    $lengthMatchTemp = (strpos($text, "<", $iPos) - $iPos);
                    if ($lengthMatchTemp > 0) {
                        $lengthMatch = ($lengthMatchTemp < $lengthMatch)? $lengthMatchTemp: $lengthMatch;
                    }
                    
                    $lengthMatchTemp = (strpos($text, "\r", $iPos) - $iPos);
                    if ($lengthMatchTemp > 0) {
                        $lengthMatch = ($lengthMatchTemp < $lengthMatch)? $lengthMatchTemp: $lengthMatch;                    
                    }

                    $lengthMatchTemp = (strpos($text, "\n", $iPos) - $iPos);
                    if ($lengthMatchTemp > 0) {
                        $lengthMatch = ($lengthMatchTemp < $lengthMatch)? $lengthMatchTemp: $lengthMatch;                                        
                    }
                    
                    $lengthMatchTemp = (strlen($text) - $iPos);
                    if ($lengthMatchTemp > 0) {
                        $lengthMatch = ($lengthMatchTemp < $lengthMatch)? $lengthMatchTemp: $lengthMatch;                                                                                                          
                    }

                    $match = substr($text, $iPos, $lengthMatch);
                    
                    $lengthMatch = strlen($match);
                    $text = substr_replace($text, " <a href=\"{$match}\">{$match}</a> ", $iPos, $lengthMatch);
                    
                    $lengthMatch = (2 * strlen($match)) + (strlen('<a href=""></a>'));

                }

            } else {
                $lengthMatch = 0;
            }
            
            $iPos = $iPos + $lengthMatch;

        } while (($iPos >= 0) && ($iPos < strlen($text)));
        
        return $text;

    }
?>