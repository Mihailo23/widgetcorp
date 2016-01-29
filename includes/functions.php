<?php 

	function confirm_query($result_set) {
		if (!$result_set) {
			die("Database query failed");
		}		
	}

	function find_all_subjects() {
		global $connection;

		$query  = "SELECT * ";
		$query .= "FROM subjects ";
		// $query .= "WHERE visible = 1 ";
		$query .= "ORDER BY position ASC";
		$subject_set = mysqli_query($connection, $query);
		confirm_query($subject_set);
		return $subject_set;
	}

	function find_pages_for_subject($subject_id) {
		global $connection;

		// we take $subject_id from $_GET and then we use it in our query, that's why we have to secure that value, otherwise, user can try and attempt an sql injection
		$safe_subject_id = mysqli_real_escape_string($connection, $subject_id);
		
		$query  = "SELECT * ";
		$query .= "FROM pages ";
		$query .= "WHERE visible = 1 ";
		$query .= "AND subject_id = {$safe_subject_id} ";
		$query .= "ORDER BY position ASC";
		$page_set = mysqli_query($connection, $query);
		confirm_query($page_set);
		return $page_set;
	}

	function find_subject_by_id($subject_id) {
		global $connection;

		// we take $subject_id from $_GET and then we use it in our query, that's why we have to secure that value, otherwise, user can try and attempt an sql injection
		$safe_subject_id = mysqli_real_escape_string($connection, $subject_id);

		$query  = "SELECT * ";
		$query .= "FROM subjects ";
		$query .= "WHERE id = {$safe_subject_id} ";
		$query .= "LIMIT 1";
		$subject_set = mysqli_query($connection, $query);
		confirm_query($subject_set);
		// here we can do mysqli_fetch_assoc immediately, because we have only one row of data
		// and if we don't get any values, we return null, as in nothing
		if ($subject = mysqli_fetch_assoc($subject_set)) {
			return $subject;
		} else {
			return null;
		}
	}

	function find_page_by_id($page_id) {
		global $connection;

		$safe_page_id = mysqli_real_escape_string($connection, $page_id);

		$query  = "SELECT * ";
		$query .= "FROM pages ";
		$query .= "WHERE id = {$safe_page_id} ";
		$query .= "LIMIT 1";
		$page_set = mysqli_query($connection, $query);
		confirm_query($page_set);

		if ($page = mysqli_fetch_assoc($page_set)) {
			return $page;
		} else {
			return null;
		}
	}

	// navigation takes two arguments
	// - the currently selected subject ID (if any)
	// - the currently selected page ID (if any)
	function navigation($subject_id, $page_id) {
		$output = "<ul class=\"subjects\">";
		$subject_set = find_all_subjects();
		// 3. Use returned data (if any)
		while ($subject = mysqli_fetch_assoc($subject_set)) {
			// output data from each row
			$output .= "<li";
			if ($subject["id"] == $subject_id) {
				$output .= " class=\"selected\"";
			}
			$output .= ">";
			$output .= "<a href=\"manage_content.php?subject=";
			$output .= urlencode($subject['id']);
			$output .= "\">";
			$output .= $subject["menu_name"];
			$output .= "</a>";

			$page_set = find_pages_for_subject($subject["id"]);
			$output .= "<ul class=\"pages\">";

			// 3. Use returned data (if any)
			while ($page = mysqli_fetch_assoc($page_set)) {
				// output data from each row
				$output .= "<li";
				if ($page["id"] == $page_id) {
					$output .= " class=\"selected\"";
				}
				$output .= ">";
				$output .= "<a href=\"manage_content.php?page=";
				$output .= urlencode($page['id']);
				$output .= "\">";
				$output .= $page["menu_name"];
				$output .= "</a>";
				$output .= "</li>";
			}
			// 4. Release returned data
			mysqli_free_result($page_set);
			$output .= "</ul>";
			$output .= "</li>";
		} 
		// 4. Release returned data
		mysqli_free_result($subject_set);
		$output .= "</ul>";

		return $output;
	}

?>