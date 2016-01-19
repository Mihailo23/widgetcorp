<?php

// prisutnost
function has_presence($value) {
	return isset($value) && $value !== ""; // true/false
}


// maximalna duzina
function has_max_length($value, $max) {
	return strlen($value) <= $max;
}


// set vrednosti
function has_inclusion_in($value, $set) {
	return in_array($value, $set);
}

function validate_max_lengths($fields_with_max_lengths) { // asocijativni niz
	global $errors;
	foreach ($fields_with_max_lengths as $field => $max) {
		if (!has_max_length($field, $max)) {
			$errors['field'] = ucfirst($field) . " is too long";
		}
	}
}

// prikaz gresaka
function form_errors($errors = array()) {
	$output = "";
	if (!empty($errors)) {
		$output .= "<div class=\"error\">";
		$output .= "Please fix the following errors:";
		$output .= "<ul>";
		foreach ($errors as $key => $error) {
			$output .= "<li>";
			$output .= $error;
			$output .= "</li>";
		}
		$output .= "</ul>";
		$output .= "</div>";
	}
}


?>