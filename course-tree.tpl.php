<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-54bfb84d6154ac67" async="async"></script>
<?php
    // ------------------------------------------
    //	Create the AtoZ list. 
    // ------------------------------------------
    function createAtoZ(&$azLinks, $courses) {

        $firstCharacter = '';
        $iTotalCourses = 0;

        $azLinks = array_combine($azLinks, array_fill(0, count($azLinks), 0));

        foreach ($courses as $subject) {
            foreach ($subject['subjects'] as $cat) {
                foreach ($cat['courses'] as $ci) {
                    $firstCharacter = strtoupper(substr($ci->Description, 0, 1));
                    if (array_key_exists($firstCharacter, $azLinks)) {
                        $azLinks[$firstCharacter]++;
                    } elseif (array_key_exists($firstCharacter, range(0, 9))) {
                        $azLinks['0-9']++;
                    }
                    $iTotalCourses++;
                }
            }
        }

        $azLinks['All'] = $iTotalCourses;
    }

    // ------------------------------------------
    //	Print the AtoZ list. 
    // ------------------------------------------
    function printAtoZ($azLinks, $character = '', $qualification = '') {

        $firstCharacter = '';

        print('<ul id="aToz">');
        foreach ($azLinks as $firstCharacter => $value) {
            print ("<li>");

            // To highlight the selected character REMOVED 20-05-14 links to row 52
           // if ($character === $firstCharacter) {
            //    print("<strong>");
           // }

            if ($value >= 1) {
                print l($firstCharacter, 'course/browse/' . $firstCharacter . '/' . $qualification, array('attributes' => array('title' => ($value . ' courses'))));
            } else {
                print("<span class=\"empty\">{$firstCharacter}</span>");
            }

           // if ($character === $firstCharacter) {  REMOVED 20-05-14 
             //   print("</strong>");
           // }

            print ("</li>");
        }
        print('</ul><p>&nbsp;</p>');
    }

    // ------------------------------------------
    //	Beginning of the script 
    // ------------------------------------------
    // --------------------------------------------------------------------------------------
    // Print the title of the page: 
    // 
    // Options are 
    //  A-> NOT TILE. WHEN A CATEGORY IS PASSED DON'T SHOW TITLE, MEANS WE ARE WITHIN A NODE.
    //  B-> BROWSE QUALIFICATION COURSES 
    //  C-> BROWSE COURSES
    // --------------------------------------------------------------------------------------
    
    if (!isset($category) || $category == '') {
        if (isset($qualification) && $qualification !== '') {
            print("<h1>Browse Qualification Courses for 2013/2014</h1>");// ability to set qualification courses not used 
        } else {

           print("<h1>Browse Courses</h1>");
        }
    }

    // ------------------------------------------
    // Variables Initialisation
    // ------------------------------------------
    $azLinks = null; // array that will hold the AtoZ data
    $closeLi = $isAtoZ = false;
    $firstLi = true;
    $prevCourse = $prevInstance = '';
    $iContCat = 1;

    // -------------------------------------------------------------------------
    // WHEN A CATEGORY IS PASSED DON'T SHOW A TO Z, MEANS WE ARE WITHIN A NODE.
    // -------------------------------------------------------------------------
    if (!isset($category) || $category == '') {
        // ------------------------------------------
        // Create AtoZ array
        // ------------------------------------------
        $azLinks = range('A', 'Z');
        array_unshift($azLinks, "0-9");

        // ------------------------------------------
        // Populate AtoZ array
        // ------------------------------------------
        createAtoZ($azLinks, $courses);

        // ------------------------------------------
        // Print AtoZ array
        // ------------------------------------------
        printAtoZ($azLinks, $character, $qualification);

        // ------------------------------------------
        // Check if we are in AtoZ.
        // ------------------------------------------
        if ($character != '' && strtolower($character) != 'all') {
            $isAtoZ = ((strlen($character) == 1) && ctype_alpha($character)) || $character == '0-9';
            if (!$isAtoZ) {
                $character = 'All'; // We ignore anything that is not A-Z or a-z.
            }
        }
    }

    // ------------------------------------------
    //	Print the courses
    // ------------------------------------------


    print ($isAtoZ ? '<ul id="browser">' : '<div id="course_listing">');
    
    foreach ($courses as $subjectarea) {
        
        // Check the subject area has subjects
        if (!count($subjectarea['subjects'])) {
            continue;
        }
        
        if (!$isAtoZ) {
            printf('<div id="cat_%s">', $iContCat++);
			if(!isset($add_js) || $add_js != false) {

				printf('<h2 class="area_section_title">%s</h2>', $subjectarea['catDes']);
			}
			print('<div class="area_container" ' . ((!isset($add_js) || $add_js != false)? 'style="display: none;"': '') . '>' . '<ul class="area_list">');
        }
    
        $rowA = true;
        foreach ($subjectarea['subjects'] as $cat) {

            // Check the category has courses
            if (!count($cat['courses'])) {
                continue;
            }
            
            if (!$isAtoZ) {
                
                // Calculate row colour
                $row_class = $rowA? 'row_a': 'row_b';                
                $rowA = !$rowA;
                
                printf('<li class="subject_section_listing %s">', $row_class);
                if(!isset($add_js) || $add_js != false) {
                	printf('<h3 class="subject_section_title">%s</h3>', $cat['catDes']);
                }                
                print('<ul class= "course_instance_listing" ' . ((!isset($add_js) || $add_js != false)? 'style="display: none;"': '') . '>');
            }

            print "<li>";
            $closeLi = false;
            $firstLi = true;

            foreach ($cat['courses'] as $ci) {

                // --------------------------------------------------------------------
                //	If is AtoZ and the first letter is not the one selectect continue
                // --------------------------------------------------------------------
                $firstCharacter = strtoupper(substr($ci->Description, 0, 1));
                If ($isAtoZ && $firstCharacter != $character) {
                    if (!($character == '0-9' && array_key_exists($firstCharacter, range(0, 9)))) {
                        continue;
                    }
                }

                list($courseCode, $courseInstance) = explode("-", $ci->Code); // divide the course code in two parts code/instance

                $closeLi = ($prevCourse !== $courseCode);

                if ($closeLi && !$firstLi) {
                    print "</li>";

                    if ($prevCourse == $courseCode && $prevInstance == $courseInstance) {
                        // Some courses appear in several categories, with this we avoid empty spaces on the list.
                        continue;
                    } else {
                        print '<li>';
                    }
                }

                // Append details to an existing course/node or create a new one.
                if ($prevCourse !== $courseCode) {
                    print ("<span style=\"font-weight:bold;\">" . $ci->Description . "</span>");
                }
                print ("<span style=\"font: 12px/16px Arial, Verdana, Tahoma, Helvetica;\">");

                // -------------------------------------------------------
                // If CusMemo1 is flagged (x) do not show the course info     strtolower($node->CusMemo1) != 'x'
                // -------------------------------------------------------                    
                if (strtolower($ci->CusMemo1) != 'x') {
                    // It can be the case that the course runs in more than one day
                    // but all of them at the same time so we only show the dayarray
                    // but the same times
                    $sKey = $ci->CusDate1 . $ci->CusDate2;
                    print ("<br/>" . l($ci->Code, 'course/' . $ci->Code) . ' <span style="margin-top: 7px;"> '  
                    	    . substr($ci->CusDate1, 0, 5) . ' - ' . substr($ci->CusDate2, 0, 5) . ' | ' . $ci->Start . ' | ' 
                    	    . $ci->Days . ' | ' . $ci->Weeks . ($ci->Weeks > 1? " wks": " wk") 
                    	    . ' | Fee: £' . $ci->fullfee . 
                    	    (!isset($ci->concessionary)? "": ' | Conc: £' . $ci->concessionary) . '</span>');
                } else {
                    $year = "20" . substr($courseInstance, 0, 2) . "/20" . substr($courseInstance, 2, 2);
                    print ("<br/>" . l($ci->Code, 'course/' . $ci->Code) . '<span style="margin-top: 7px;"> ' . 'this part of our ' . $year . ' curriculum offer.</span>');
                }
                print ("</span>");

                $closeLi = true;
                $firstLi = false;

                $prevCourse = $courseCode;
                $prevInstance = $courseInstance;
            }
            print "</li>";
            if (!$isAtoZ) {
                print "</ul></li>";
            }
        }
        print "</div>";
        if (!$isAtoZ) {
            print "</ul></li></div>";
        }      
    }

    print ($isAtoZ ? '</ul>' : '</div>');
			?><div class="addthis_sharing_toolbox"></div><?php  
	if(!isset($add_js) || $add_js != false) {
    
?>

<script type="text/javascript">
// <![CDATA[
	jQuery(document).ready(function() {

		jQuery(".course_instance_listing").hide();

  		jQuery(".subject_section_title").click(function() {
  			jQuery(this).next(".course_instance_listing").slideToggle(500);
  		});
  	});

// ]]>
</script>
<script type="text/javascript">
// <![CDATA[
	jQuery(document).ready(function() {

		jQuery(".area_container").hide();

  		jQuery(".area_section_title").click(function() {
  			jQuery(this).next(".area_container").slideToggle(500);
  		});
  	});

// ]]>
</script>

<?php } ?>