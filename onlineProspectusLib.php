<?php

	define("TABLE_DECLARATION_INI", 0);
	define("TABLE_DECLARATION_END", 1);
	
	define("COURSE_TYPE_ONE_DAY", 2);
	define("COURSE_TYPE_TWO_DAYS", 3);
	define("COURSE_TYPE_SATURDAYS", 4);
	define("COURSE_TYPE_CATEGORY", 5);
	define("COURSE_TYPE_SUBCATEGORY", 6);
	
	$link = null;

	function printTableStructure($table_section) {
		
		switch ($table_section) {
			case TABLE_DECLARATION_INI:
				echo ('<table id="listingTable">');
				break;
			case TABLE_DECLARATION_END:
				echo ('</table>');
				break;		
		}
		
	}

	function printTableHeader() {
		
		$tableHeader = "";
		ob_start();
?>
		<thead>
			<tr>
				<th colspan="7" class="courseTitle">Course Title</th>
			</tr>
			<tr>
				<th style="width: 200px;">Course <br />Code (Site)</th>
				<th>Start/ End <br />Date</th>
				<th>Week(s)</th>
				<th>Day(s)</th>
				<th>
					Start/End<br />
					Time
				</th>
				<th>EU <br />Price</th>
				<th>Non-EU <br />Price</th>
			</tr>
		</thead>
<?php 
		$tableHeader = ob_get_clean();
		echo ($tableHeader);
	}

	
	function printTableBody($data) {
		
		$tableBody = "";
		ob_start();
		
		echo ("<tbody>");
		printData($data);
		echo ("</tbody>");
		$tableBody = ob_get_clean();

		echo ($tableBody);
	}	
	
	function getData($query_type, $parameters = ""){
		
		$courses = null;
		$course = null;
		$prevCode = "";
		
		$sql = "SELECT	 ifnull((select p.category from categories p, categories s where p.id = s.parentID and s.id = i.custext2), 'Miscellaneous') as category, ";
		$sql .= "        i.code, i.siteid, i.description as title, DATE_FORMAT(STR_TO_DATE(i.start, '%d/%m/%Y'), '%d/%m/%y') as startdate, ";
		$sql .= "        DATE_FORMAT(DATE_ADD(STR_TO_DATE(i.start, '%d/%m/%Y'), INTERVAL i.weeks WEEK), '%d/%m/%y') as enddate, i.weeks, i.days, ";         
		$sql .= "        TIME_FORMAT(cusdate1, '%H:%i') as starttime, TIME_FORMAT(cusdate2, '%H:%i') as endtime, ";
		$sql .= "        (f.fullfee - IFNULL(fd.discountvalue, 0)) as euprice, IFNULL(neu.noneu, 0) as noneuprice "; 
		$sql .= "FROM    courseinfo i ";
		$sql .= "        LEFT JOIN fullcoursefees f USING (code) ";         
		$sql .= "        LEFT JOIN noneufees neu USING (code) ";   
		$sql .= "        LEFT JOIN fullfeedisc fd USING (code) "; 
		$sql .= "WHERE   ((i.Status <> 'CLOS' OR i.Status IS NULL) AND i.VLE <> '0') "; 

		switch ($query_type){
			case COURSE_TYPE_ONE_DAY:
				$sql .= "AND (i.weeks = 1 AND LENGTH(TRIM(i.days)) = 3 AND i.days != 'sat') ";
				break;
			case COURSE_TYPE_TWO_DAYS:
				$sql .= "AND (i.weeks = 1 AND LENGTH(TRIM(i.days)) BETWEEN 3 AND 8) ";
				break;
			case COURSE_TYPE_SATURDAYS:
				$sql .= "AND i.days = 'sat' ";
				break;
			case COURSE_TYPE_CATEGORY:
				$sql .= "AND i.custext3 = " . $parameters . " ";
				break;
			case COURSE_TYPE_SUBCATEGORY:
				$sql .= "AND i.custext2 = " . $parameters . " ";
				break;				
		}

		$sql .= "AND STR_TO_DATE(i.start, '%d/%m/%Y') >= NOW() "; 
		$sql .= "ORDER BY category, i.code, title ";	
		
		$result = mysql_query($sql);
		while($row = mysql_fetch_object($result)) {
		
			list($code, $instance) = explode("-", $row->code);
			
			if (empty($prevCode) || ($prevCode != $code)) { 
				$course[$instance] = $row;
				$courses[$row->category][$code] = array($row->title, $course);
				if ($prevCode != $code) {$course = null;}
			} else {
				$course[$instance] = $row;
			} 
			$prevCode = $code;
						
		}
		
		printTableBody($courses);
		
	}
	
	function openDBConnection() {
		
		//$link = mysql_connect('localhost', 'drupal', 'a9de51g9');
		$link = mysql_connect('spruce', 'query', 'query');
		if (!$link) {
			die ('Connection Error');
			return false;
		}
		
		if (!mysql_select_db('raccdevelop')){
			die ('Database Error');
			return false;
		}
		
		return true;
		
	}
	
	function printData($data) {
		
		foreach ($data as $category => $courses) {

			echo ("<tr><td colspan=\"7\"><h2>{$category}</h2></td></tr>");			

			foreach ($courses as $course) {
				
				list($title, $instances) = $course;
				echo ("<tr><td colspan=\"7\" class=\"courseTitle\">{$title}</td></tr>");
				
				foreach ($instances as $instance) {
					$httpURL = '<a href="http://www.racc.ac.uk/course/' . $instance->code . '">' . $instance->code . "</a>";
					echo ("<tr><td>{$httpURL} ({$instance->siteid})</td>");
					echo ("<td>{$instance->startdate}<br />");
					echo ("{$instance->enddate}</td>");
					echo ("<td>{$instance->weeks}</td>");
					echo ("<td>{$instance->days}</td>");
					echo ("<td>{$instance->starttime}<br />");
					echo ("{$instance->endtime}</td>");
					echo ("<td>{$instance->euprice}</td>");
					echo "<td>", is_null($instance->noneuprice)? $instance->euprice: $instance->noneuprice, "</td></tr>";	
				}
				
				echo ("<tr><td colspan=\"9\"><hr /></td></tr>");
			}
		}		
		
	}
?>
