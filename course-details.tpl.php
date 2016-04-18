<?php
require_once ("sites/all/libraries/custom/linkParserLib.php");

drupal_add_css(path_to_theme() . '/search.css', 'theme');
drupal_add_css(path_to_theme() . '/style.css', 'theme');

$node->nid = $node->Code;
$node->ac_course_code = $node->Code;
// -----------------------------------------------------------
//  $year is used when the course has not got set dates
// -----------------------------------------------------------
$parts = explode("-", $node->Code);
$year = "20" . substr($parts[1], 0, 2) . "/20" . substr($parts[1], 2, 2);

$title = $node->Description;
$node_url = "course/$node->nid";

$excerpt = course_app_clearmarkup(linkParser(check_plain($node->Overview)));
$excerpt_length = 275;
if (strlen($excerpt) > $excerpt_length) {
    $excerpt = substr($excerpt, 0, $excerpt_length) . l(' ...', $node_url);
}

$status = $node->Status;
if ($status == 'NOT CLOSED') {
    $status = 'PLACES AVAILABLE';
}
?>
<!-- Go to www.addthis.com/dashboard to customize your tools -->
<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-54bfb84d6154ac67" async="async"></script>

<?php
// --------------------------
// TEASER -> SEARCH RESULTS
// --------------------------
if ($teaser) {
    ?>

    <div id="node-<?php print $node->nid; ?>" class="result site <?php print strtolower($node->cuslook1) ?>">
        <?php print $picture ?>
        <div class="search-result-header">
            <div class="search-result-title">
                <a href="<?php print url($node_url); ?>" title="<?php print $title; ?>">
                    <?php print $title; ?>&nbsp;(<?php print trim($node->Code); ?>)
                </a>
            </div>  
            <div class="search-result-title-data">
                <div class="search-result-xtra">
                    <p>
                        <?php
                        // --------------------------------------------------------
                        // If CusMemo1 is flagged with X, don't show the location,
                        // days and times info.
                        // --------------------------------------------------------
                        if (strtolower($node->CusMemo1) == 'x') {
							
							   ?>
                           <!-- <strong>Starts:</strong>-->
                            <?php print $node->Start; ?>
                            ,
                            <?php print (isset($node->daystimes) ? $node->daystimes : $node->Days); ?>
                            ,
                            <?php print $node->CusDate1; ?>
                            to
                            <?php  print $node->CusDate2;			//not released			
							
                            print ("<span class=\"alert\"> - This course is part of our {$year}</span>");
                        
                     
					// --------------------------------------------------------------------End--------------------	
					// courses which have an expired start date.				
					}	else if(strtotime(str_replace("/", "-", $node->Start)) < strtotime(date("d-m-Y"))){
                        ?>
                                                    <strong>Starts:</strong>
                            <?php print $node->Start; ?>
                            ,
                            <?php print (isset($node->daystimes) ? $node->daystimes : $node->Days); ?>
                            ,
                            <?php print $node->CusDate1; ?>
                            to
                            <?php
                            print $node->CusDate2;?>
                        <p class="alert">
                            It may be possible to join some courses after the start date.<br /> 
                            Please email 
                            <a href="mailto:info@racc.ac.uk?subject=Late Enrolment - <?php print(trim($title)); ?> (<?php print(trim($node->Code)); ?>)">
                                info@racc.ac.uk
                            </a> to enquire.
                        </p>
                        
                        <?php
											// --------------------------------------------------------------------End--------------------	
                    } else {//added course details for 2014/15 courses prior to enrolment but details ready, remove down to comment (//not released) if full details aren't ready
                          ?>
                            <strong>Starts:</strong>
                            <?php print $node->Start; ?>
                            ,
                            <?php print (isset($node->daystimes) ? $node->daystimes : $node->Days); ?>
                            ,
                            <?php print $node->CusDate1; ?>
                            to
                            <?php
                            print $node->CusDate2;
                        }
                        ?>
                    </p>

                </div>                    
                <div class="collapsible-title">More</div>
            </div>
        </div>
        <div class="collapsible-content">
        
        
        <?php  /*     
   if($node->Centre==""){
   ?>       <p class="college">
                <strong>Location: Richmond Parkshot Road   
            </p>

   <?php
   }
   else {      
  ?>        <p class="college">
                <strong>Location:</strong>&nbsp;<?php print $node->Centre; ?>   
            </p>
            
     <?php          
       }  
	     */?>   
            <p>
                <strong>Overview:</strong>
                <?php print $excerpt ?>
            </p>
            <p class="actions">
                <a class="information" href="<?php print url($node_url) ?>">Details</a> 
            </p>

            
            
        </div>    
    </div>
    <?php
} else 
   {
        /*************
        Fork B:Start (Default page)
        *************/
        ?>
     <div class="details">
        <div id="buttons_top" style="margin-bottom: 1.25em;">
        <?php
        
/*** lucy changed code on 280116 and removed  if(!in_array($node->Code, $chamber_courses))*/
        if($node->Code)
       		 {
				if (!$node->is_closed) {
					if ($node->is_full && $node->WarnEnr == 1) {
						?>
							<form style="display: inline-block;" method="GET" action="http://racc.eenrol.co.uk/admissions/">
							<input type="hidden" name="ac_course_code" value="<?php print($node->ac_course_code); ?>">
								  <input type="hidden" value="APPLY" name="app_type">
							<input type="submit" value="Apply Now" id="apply-btn" class="course-btns action-btn">
							</form>                    
						<?php
					} else if(strtotime(str_replace("/", "-", $node->Start)) < strtotime(date("d-m-Y"))) {
							  ?>      
							<form style="display: inline-block;" method="GET" action="/enquiry-form">
								<input type="hidden" name="code" value="<?php print($node->Code); ?>">
								<input type="hidden" name="desc" value="<?php print(trim($node->Description)); ?>">
								<input type="hidden" name="date" value="<?php print($node->Start); ?>">
								<input type="submit" value="Course Enquiry" class="course-btns">
							 </form>            
						<?php
					}elseif ($node->WarnEnr == 1 && strtolower($node->CusMemo1) != 'x') {
                          ?>      
                         <form style="display: inline-block;" method="GET" action="http://racc.eenrol.co.uk/admissions/">
                              <input type="hidden" name="ac_course_code" value="<?php print($node->ac_course_code); ?>">
                              <input type="hidden" value="APPLY" name="app_type">
                              <input type="submit" value="Apply Now" id="apply-btn" class="course-btns action-btn">
            			</form>   
                        <form style="display: inline-block;" method="GET" action="/enquiry-form">
               				<input type="hidden" name="code" value="<?php print($node->Code); ?>">
                			<input type="hidden" name="desc" value="<?php print(trim($node->Description)); ?>">
               				<input type="hidden" name="date" value="<?php print($node->Start); ?>">
                			<input type="submit" value="Course Enquiry" class="course-btns">
           				 </form>            
            			<?php
                	} elseif ($node->is_full) {
                  		 ?>
                        <form style="display: inline-block;" method="GET" action="http://racc.eenrol.co.uk/admissions/">
                             <input type="hidden" name="ac_course_code" value="<?php print($node->ac_course_code); ?>">
                             <input type="hidden" value="YES" name="waiting_list">
                             <input type="submit" value="Register on Waiting List" class="course-btns action-btn">
                        </form>  
                    	<?php
                	} elseif (strtolower($node->CusMemo1) != 'x') {
                    	?>
                        <form style="display: inline-block;" method="GET" action="http://racc.eenrol.co.uk/admissions/">
                            <input type="hidden" name="ac_course_code" value="<?php print($node->ac_course_code); ?>">
                            <input type="hidden" value="ENROL" name="app_type">                       
                        	<input type="submit" value="Enrol Now" class="course-btns action-btn">
                        </form>     
						<form style="display: inline-block;" method="GET" action="/enquiry-form">
               				 <input type="hidden" name="code" value="<?php print($node->Code); ?>">
                			<input type="hidden" name="desc" value="<?php print(trim($node->Description)); ?>">
               				 <input type="hidden" name="date" value="<?php print($node->Start); ?>">
                			<input type="submit" value="Course Enquiry" class="course-btns">
            			</form>                                 
                    	<?php
                	}  elseif (strtolower($node->CusMemo1) == 'x' && $node->WarnEnr == 1 ) {
                   		 ?> <form style="display: inline-block;" method="GET" action="http://racc.eenrol.co.uk/admissions/">
                              <input type="hidden" name="ac_course_code" value="<?php print($node->ac_course_code); ?>">
                              <input type="hidden" value="APPLY" name="app_type">
                              <input type="submit" value="Apply Now" class="course-btns action-btn">
            			</form> 
						<form style="display: inline-block;" method="GET" action="/enquiry-form">
               				 <input type="hidden" name="code" value="<?php print($node->Code); ?>">
                			<input type="hidden" name="desc" value="<?php print(trim($node->Description)); ?>">
               				 <input type="hidden" name="date" value="<?php print($node->Start); ?>">
                			<input type="submit" value="Course Enquiry" class="course-btns">
            			</form>                                 
                    	<?php
               		 }elseif (strtolower($node->CusMemo1) == 'x') {
                    	?> 	<form style="display: inline-block;" method="GET" action="/enquiry-form">
               				 <input type="hidden" name="code" value="<?php print($node->Code); ?>">
                			<input type="hidden" name="desc" value="<?php print(trim($node->Description)); ?>">
               				 <input type="hidden" name="date" value="<?php print($node->Start); ?>">
                			<input type="submit" value="Course Enquiry" class="course-btns">
            			</form>                                 
                   		<?php
                	}
				
            	}
            
       		}
			?>        
                    <!--  <form style="display: inline-block;" method="GET" action="<?php print url('course/pdf/' . $node->Code) ?>">
                    <input type="submit" value="Download Outline">
                    </form>    prints pdf but through a PDO exception on new server 03/12/2015  -->           
                     <form style="display: inline-block;" method="GET" action="#">
                    <input type="submit" value="Print" class="course-btns" onclick="window.print();">
                    </form>            
                   
                   
                    <form style="display: inline-block;" method="GET" action="#">
                    <input type="submit" value="Email to Friend" class="course-btns" onclick="return addthis_sendto('email');">
                    </form>                          
			</div>
 
       <?php
        // --------------------------------------------------------
        // If CusMemo1 is flagged with X, don't show the location,
        // days and times info neither the enrolment links.
        // --------------------------------------------------------
        	if (strtolower($node->CusMemo1) != 'x'){
       ?>
              <p>
                <strong>Availability:</strong>
					<?php
                    	if ($node->is_full || $node->is_closed) {
                     ?>
                        	No (<a class="popup" title="More Info" target="_blank" href="/course-availability">More info</a>)
                    <?php
                		} else {
                    ?>
                   		 	Yes
                    <?php
                }
                ?>
              </p><p>
                 <strong>Price:</strong> 
                    <?php 
						if(trim($node->Code) == 'L00478-141501'){
					?>
                   			Prices vary according to the duration of the course.</p>
                    <?php }
                       else if($node->fullFee == "TBA" || $node->Concessionary_Fee == 99999)
                        {
                            echo 'TBA (Concessionary/non-EU fees to be determined)';
                        }

					// courses which have an expired start date.				
                       else {
								//Full fee
								echo '&pound;';
								printf('%01.2f', (int) $node->fullFee - (int) $node->fullFeeDiscount);
								//Concessionary
								if($node->Concessionary_Fee !== NULL)
										{
											echo ' concessionary fee, ';
											echo '&pound;';
											printf( '%01.2f', (int) $node->Concessionary_Fee);
										}
                        	}
                    ?>
                    
                    <span style="display: block; font-size: smaller; padding-left: 0.25em;">Click here for <a href="/course-fees" alt="Help with Fees">information on help with fees</a></span>

            	</p>
            <p>   
            
                    <?php 
					// --------------------------------------------------------------------End--------------------	
					// courses which have an expired start date.				
						if(strtotime(str_replace("/", "-", $node->Start)) < strtotime(date("d-m-Y"))){
                     ?>
                            <p class="alert">
                                    Please note: <br>This Course has already started, please have a look at the alternative dates below or it may be possible to join some courses after the start date. <br><br>
                                    If you're interested in joining this course email  <a href="mailto:info@racc.ac.uk?subject=Late Enrolment - <?php print(trim($title)); ?> (<?php print(trim($node->Code)); ?>)">
                                    info@racc.ac.uk </a> to enquire.<br><br>
                            </p>
   					<!--Day/time Section-->          
                             <p>
                                <strong>Weekdays/Time:</strong>
                                <?php
                                print ($node->daystimes);
                                ?>
                            </p>
                     <!--Start Date Section-->       
                            <p>	
                            	<strong>Started:</strong>
                                <?php print $node->Start; ?>   
                               (Number of Weeks: <?php print $node->Weeks ?>)
                            </p> 
                     <!--End Date Section-->         
                            <p>
                                <strong>End Date:</strong>
                                <?php
                                print ($node->end_date);
                                ?>
                            </p>

                        
                        <?php
    // --------------------------------------------------------
    // Implementation of alternative dates
    // --------------------------------------------------------        
  						if (isset($node->AltInstances)) {
       
								if (isset($node->Payg)) {  ?>
                                            <h3 class="alternative_switch no-top-border">Click here to see PAYG Dates &rsaquo;</h3>
                                     <?php  } 
								else {
								?>
										<h3 class="alternative_switch no-top-border"  id="altdate">Click here to see Alternative Dates &rsaquo;</h3><br><br>
								<?php }
								?>
				  		<!--Start of course listing-->  
								<div class="alternative_container">
								  	<ul style="list-style-type: none; margin-left: 10px;">
						  		<?php
								 		foreach ($node->AltInstances as $altCourse){
										  $tmpTxt = "";
										  $tmpTxt .= sprintf('<a href="/course/%s" title="%s">%s</a> %s <em>Start:</em> %s<br/> <em>Duration:</em> %s week(s) <em>Days/Time</em>: %s', $altCourse['code'], $altCourse['desc'], $altCourse['code'], $altCourse['desc'], $altCourse['date'], $altCourse['weeks'], empty($altCourse['days']) ? " tbc " : $altCourse['days']);
										  printf("<li>" . rtrim($tmpTxt, ",") . "</li>");
          		      			}
          		      			?>
           				 			</ul>
       				 			</div>
        					<?php   
 			 				}

	// --------------------------------------------------------------------End--------------------
	//start of next years offer and information being displayed CustMemo1 = x	
                    	} else {
							?>  
                        <p><strong>Weekdays/Time:</strong>
                            <?php
                            print ($node->daystimes);
                            ?>
                        </p>                                             
							 <strong>Starts:</strong>
                             <?php print $node->Start; 
							 ?>     
        					 </p>
						 <?php 
                           if (!empty($node->end_date)) {
                         ?>
                        		<p>	<strong>End Date:</strong>
								<?php print ($node->end_date);
                                ?>
                        		</p>
                        <?php 
                         } else {
                            	print("empty date");
                         }
                        ?>        

                        <p>
                            <strong>Number of Weeks:</strong>
                            <?php print $node->Weeks ?>
                        </p>   
       			 		<?php
		 			}
			?>
       <!--Tutor Approval section different section for ESOL courses -->       
	        <p><strong>Tutor Approval Required? &nbsp;&nbsp;</strong>
            <?php //selects message for the Tutor approval coursess as ESOL has different requirements
            if (($node->WarnEnr == 1)&&(strpos($node->Description, 'ESOL') !== false)) {
                ?>
           			 Yes </p>
                     		<p>Please come into the college for enrolment. </p>
                                <ol>
                                    <li><strong>Step 1 - Assess your skills:</strong> this will take about 1hour you can come and do this <strong>at any time during <a href="/term-dates">opening times</a>.</strong></li>
                                    <li><strong>Step 2 - Interview: </strong>You will talk to a tutor to help us make sure that we place you on the right course. <a href="atoz/LNEEESOL">Interview dates</a> </li>
                                </ol>
                <?php
            } else if ($node->WarnEnr == 1) {
                ?>
           			 Yes - <a href="#tutor">more info</a></p>
                <?php
            }else {
                ?>
                No
                <?php
            }
            ?>
        </p>
        <?php
  			} else {
	// SECTION for next years courses reduced information denoted by Cusmemo1 = 'x'	
        ?>
       				 <p class="next-year">
           			    This course is part of our <?php echo($year); ?> curriculum offer. 
              		  	<br><br>Apply now for an interview.</p>
<!--  -----------course details for courses not yet open for enrolment put code below CusMemo1 ='x'----------- -->  
            		 <p>
                    <span style="display: block; font-size: smaller; padding-left: 0.25em;">Click here for <a href="/course-fees" alt="Help with Fees">information on help with fees</a></span>
            		</p>

            		<strong>Tutor Approval Required?</strong>
            <?php
            if ($node->WarnEnr == 1) {
                ?>
           			 Yes <a href="#tutor">more info</a> </p>
                     		               
			<?php
            } else {
			?>
					No 
					<?php
            		} 
           			 ?>
        	</p> 
 
        <?php
        //--------------------------Remove the code above <?php to remove unconfirmed course details when publishing basic prospectus details-----------------//   
		}

    // --------------------------------------------------------
    // Implementation of alternative dates
    // --------------------------------------------------------        
			  if (isset($node->AltInstances)AND(strtotime(str_replace("/", "-", $node->Start)) > strtotime(date("d-m-Y")))) {
				   
					if (isset($node->Payg)) {  ?>
								<h3 class="alternative_switch no-top-border">Click here to see PAYG Dates &rsaquo;</h3>
									 <?php  } 
					  else {
							?>
								<h3 class="alternative_switch no-top-border"  id="altdate">Click here to see Alternative Dates &rsaquo;</h3>
					<?php }
					?>
								<div class="alternative_container">
                                      <ul style="list-style-type: none; margin-left: 10px;">
                               			 <?php
											foreach ($node->AltInstances as $altCourse){
											$tmpTxt = "";
											$tmpTxt .= sprintf('<a href="/course/%s" title="%s">%s</a> %s <em>Start:</em> %s<br/> <em>Duration:</em> %s week(s) <em>Days/Time</em>: %s', $altCourse['code'], $altCourse['desc'], $altCourse['code'], $altCourse['desc'], $altCourse['date'], $altCourse['weeks'], empty($altCourse['days']) ? " tbc " : $altCourse['days']);
											printf("<li>" . rtrim($tmpTxt, ",") . "</li>");
										}
										?>
                                    </ul>
                                </div>
					<?php   
						 }

					?>
    </div>
    <div class="overview">
        <h3>Course Overview:</h3>
                <p>
                    <?php print course_app_clearmarkup(linkParser($node->Overview)) ?>
                </p>
        <?php        
        if ($node->WarnEnr == 1) {
                ?>
                <h3 id = "tutor">How to Apply - Tutor Approval</h3>
           			 <p>Please complete the online Application form and book your interview by clicking <strong><a href="#apply-btn">Apply Now</a> </strong>at the top of this page. If no interview slots are available at the time of your application we will be in touch to arrange an interview date. </p>
                      <p>To be accepted on the course you must complete the following steps:</p>
                                 <ol>
                                    <li><strong>Maths and English Assessments:</strong> This MUST be done prior to your interview and takes about 1hour, you can come and do this <strong>at any time during <a href="/term-dates">opening times</a>.</strong></li>
                                    <li><strong>Evidence of skills:</strong> You may be required to show evidence of relevant skills either by bringing your certificates or doing an initial assessment task</li>
                                    <li><strong>Interview: </strong>The Tutor interview is to ensure that this is the right course for you and that you are fully aware of what is involved in completing the course</li>
                                </ol>
                               <p>On completition of your interview and meeting the other criteria laid out in the application process, successful applicants will be offered a place on to the course.</p>
                <?php        
		}
		?>
        
<?php if ($node->Enrolment) { ?>
        <h3>How to Enrol:</h3>
                 <p>
                    <?php print course_app_clearmarkup(linkParser($node->Enrolment)) ?>
                </p>
            	<?php } ?>   
                        
    	<h3>Entry Requirements:</h3>             
                <p>
                    <?php print course_app_clearmarkup(linkParser($node->EntryReq)) ?>
                </p>
         		<?php //section about Skills check(intial assessment, screening) links to online tests to help students get ready
            	if ($node->WarnEnr == 1) {
                ?>
           			 <h3 id = "assessment">Maths and English Assessments</h3>
                            <p>Before your tutor interview you are also requested to take an online assessment in Maths and English at the college. This will help us ensure that the course is suitable for you and that you have the right English and maths skills to enjoy the course and succeed. </p>
                            <p>To help you, have a look at these online resources before you come to college to take the assessment.</p>
                                <ul>
                                    <li>Very confident in your English and maths -<a href="http://sta.education.gov.uk/professional-skills-tests/numeracy-skills-tests" target="_blank"> practise online tests</a></li>
                                    <li>Fairly confident in both English and maths - <a href="http://www1.edexcel.org.uk/tot/alns2/CMA-Edexcel-web2.swf" target="_blank">practise online tests</a></li>
                                    <li>Do not feel confident in either or both areas <a href="http://www.bbc.co.uk/skillswise" target="_blank">brush up your skills</a></li>
                                </ul>
                <?php
            	}       
				?>
           <?php //Section for Advanced Learner Loans 19+ 
 if ($node->AddLoan == 1) {
                ?>
          <h3 id = "assessment">Advanced Learner Loans </h3>
                <p>If this is your first full level 3 course and you are aged 19 to 23 you don’t need to take a loan as this course will be free for you.</p>
				<p>If you are aged 19 or over and have previously completed a full level 3, you can apply for an Advanced Learner Loan to cover all or part of the fee. 
                 You may be able to take more than one loan depending on the courses you want to study.</p>
                 <p><a href="advanced-learner-loans>">Find out more about the loan</a></p>
                <?php
            	}       
				?>  
       <h3>Course Content:</h3>
            	<p>
				 <?php print course_app_clearmarkup(linkParser($node->Content)) ?>
                    </p>
  <?php if ($node->Assessment) { ?>
       <h3>Assessment Methods:</h3>
                <p>
                    <?php print course_app_clearmarkup($node->Assessment) ?>
                </p>
            	<?php } ?>
  <?php if ($node->Additional) { ?>
       <h3>Additional Information:</h3>
                <p>
                    <?php print course_app_clearmarkup(linkParser($node->Additional)) ?>
                </p>
                <p>
                    <strong> For information on course fees and
                        how to get additional help to pay for them look at our <a href="/course-fees" >How to Pay for Your Course</a> section. </strong> <br />
                </p>
        		<?php } ?>
           
             
 <?php if ($node->First_class) { ?>
       <h3>Bring To First Class:</h3>
                <p>
                    <?php print course_app_clearmarkup($node->First_class) ?>
                </p>
        <?php } ?>
<?php if ($node->Attendance) { ?>
       <h3>Attendance:</h3>
                <p>
                    <?php print course_app_clearmarkup($node->Attendance) ?>
                </p>
        		<?php } ?>
<?php if ($node->Further_study) { ?>
        <h3>Further Study and Career Opportunities:</h3>
                <p>
                    <?php print course_app_clearmarkup($node->Further_study) ?>
                </p>
                
<!--  -----------START----National Careers Website links -->
                 <?php if (strpos($node->CusText2, 'BEMBAD') !== false){
                            ?>
                              <p>For further information on career opportunities take a look at the <a href="https://nationalcareersservice.direct.gov.uk/advice/planning/LMIMaps/Pages/Business.aspx" target="_blank">Business sector </a>on the <a href="https://nationalcareersservice.direct.gov.uk/advice/planning/LMI/Pages/Sector-Index.aspx" target="_blank">National Career Service website</a>.</p>
                            <?php
                            }       
							?> 
                  <?php if (strpos($node->CusText2, 'ARTDMFAD') !== false){
                            ?>
                              <p>For further information on career opportunities take a look at the <a href="https://nationalcareersservice.direct.gov.uk/advice/planning/LMIMaps/Pages/Skilled-Crafts.aspx" target="_blank">Skilled Crafts </a> or <a href="https://nationalcareersservice.direct.gov.uk/advice/planning/LMIMaps/Pages/Sport-and-Culture.aspx" target="_blank">Sport and Culture</a>sectors on the <a href="https://nationalcareersservice.direct.gov.uk/advice/planning/LMI/Pages/Sector-Index.aspx" target="_blank">National Career Service website</a>.</p>
                            <?php
                            }       
							?>    
                  <?php if (strpos($node->CusText2, 'CMTAHE') !== false){
                            ?>
                              <p>For further information on career opportunities take a look at the <a href="https://nationalcareersservice.direct.gov.uk/advice/planning/LMIMaps/Pages/Science-and-Engineering-Technicians.aspx" target="_blank">Science and Engineering Technicians</a> sectors on the <a href="https://nationalcareersservice.direct.gov.uk/advice/planning/LMI/Pages/Sector-Index.aspx" target="_blank">National Career Service website</a>.</p>
                            <?php
                            }       
							?>   
                  <?php if ((strpos($node->CusText2, 'BEMAF')!== false)||(strpos($node->CusText2, 'HECWH') !== false)){
                            ?>
                              <p>For further information on career opportunities take a look at the <a href="https://nationalcareersservice.direct.gov.uk/advice/planning/LMIMaps/Pages/Public-and-Media.aspx" target="_blank">Public and Media</a> sectors on the <a href="https://nationalcareersservice.direct.gov.uk/advice/planning/LMI/Pages/Sector-Index.aspx" target="_blank">National Career Service website</a>.</p>
                            <?php
                            }       
							?>   
                  <?php if (strpos($node->CusText2, 'RDSAC') !== false){
                            ?>
                              <p>For further information on career opportunities take a look at the <a href="https://nationalcareersservice.direct.gov.uk/advice/planning/LMIMaps/Pages/Sport-and-Culture.aspx" target="_blank">Sports and Culture</a> sectors on the <a href="https://nationalcareersservice.direct.gov.uk/advice/planning/LMI/Pages/Sector-Index.aspx" target="_blank">National Career Service website</a>.</p>
                            <?php
                            }       
							?>                             
                                                                                        
           <?php }         
        // --------------------------------------------------------
        // Implementation of similar Courses
        // --------------------------------------------------------        
        if (isset($node->SimilarCourses)) {
            ?>
            <h3 class="alternative_switch">Click here to see Alternative Courses &rsaquo;</h3>
                <div class="alternative_container">
                    <ul style="list-style-type: none;">
                        <?php
                        foreach ($node->SimilarCourses as $simCourse) {
                            ?>
                            <li>
                                <a href="/course/<?php print($simCourse->Code); ?>" title="<?php print($simCourse->Description); ?>">
                                    <?php print($simCourse->Code); ?>
                                </a> <?php print($simCourse->Description); ?> <br />
                                <em>Start:</em> <?php print($simCourse->Start); ?>
                                <em>Duration:</em> <?php print($simCourse->Weeks); ?> week<?php print((int) $simCourse->Weeks > 1 ? "(s)" : ""); ?>
                                <em>Days/Time</em>: <?php print($simCourse->daystimes); ?>
                            </li>
                            <?php
                        }
                    	?>
                            <li>
                              <a style="font-size: smaller;" href="/course/browse"  title="Search for more courses">
                                Search for more courses
                              </a>                
                            </li>
                	</ul>
            	</div>                
            <?php
        }
        ?> 
          
                
<?php if ($node->FAQ) { ?>
       <h3>Frequently Asked Questions:</h3>
                <p>
                    <?php print course_app_clearmarkup(linkParser($node->FAQ)) ?>
                </p>
            	<?php } ?>

       <h3>Information Advice and Guidance:</h3>
                <p>
                    If you are looking for assistance on a new career path, course choice or looking for further opportunities then speak to our<a href="http://www.racc.ac.uk/student-advice-support"> Information Advice and Guidance team.</a>
                </p>
</div>
<p>&nbsp;</p>

 <div class="addthis_sharing_toolbox"></div>
    <br />
    <br />
            <!-- AddThis Button BEGIN -->
			<!-- Go to www.addthis.com/dashboard to customize your tools -->

            <script type="text/javascript">
               $('.addthis_sharing_toolbox').clone().prependTo('h1.pageTitle');
            </script>

        
                <?php
   //AddThis Button END -->
        /*************
        Fork B:End (Default page)
        *************/
    }

?>
<script type="text/javascript">
    function sendMail() {
        var link = "mailto:info@racc.ac.uk"
                 + "?subject=<?php print('Course Enquire: ' . $node->Code);?>" 
                 + "&body=<?php print $title?>";
        window.location.href = link;
    }            
</script>
<script type="text/javascript">
// <![CDATA[
	jQuery(document).ready(function() {

		jQuery(".alternative_container").hide();

  		jQuery(".alternative_switch").click(function() {

  			jQuery("div.alternative_container").slideToggle(500);
  		});
  	});

// ]]>
</script>
