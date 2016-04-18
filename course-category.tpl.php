<!-- Go to www.addthis.com/dashboard to customize your tools -->
<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-54bfb84d6154ac67" async="async"></script>
<!-- Go to www.addthis.com/dashboard to customize your tools -->

<?php

    // ------------------------------------------
    //	Themes the Category/Subcategory Listing 
    // ------------------------------------------
	
    // ------------------------------------------
    // Variables Initialisation
    // ------------------------------------------
    $rowA = true;
    
    // ------------------------------------------
    //	Print the categories/subcategories
    // ------------------------------------------
    print ('<ul id="category_listing">');
    
    foreach ($categories as $key => $description) {
        
		// Check the category/subcategory has a description
        if (!isset($description) || empty($description)) {
            continue;
        }
        
        // Calculate row colour
        $row_class = $rowA? 'row_a': 'row_b';
        $rowA = !$rowA;
        
        if ($topCat == true){
        	printf('<li class="subject_section_listing %s"><div id="cat_%s">' .
        			'<h2 class="area_section_title">' .
        			l($description, 'course/atoz/' . $key) .
        			'</h2></div></li>', $row_class, $key);        	
        } else {
        	printf('<li class="subject_section_listing %s"><div id="cat_%s">' .
        			'<h3 class="subject_section_title">' .
        			l($description, 'course/atoz/' . $key) .
        			'</h3></div></li>', $row_class, $key);        	
        }
        	
	}

    print ('</ul>');
    
?>
<div class="addthis_sharing_toolbox"></div>
