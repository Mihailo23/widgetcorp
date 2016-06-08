<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php include("../includes/layouts/header.php"); ?>
<?php find_selected_page(); ?>

<div id="main">
	<div id="navigation">
	<br>
	<a href="admin.php">&laquo; Main menu</a>
	<br>
		<?php echo navigation($current_subject, $current_page); ?>
		<br>
		<a href="new_subject.php">+ Add a subject</a>
	</div>
	<div id="page">
		<?php echo message(); ?>
		<?php if ($current_subject) { ?>
			<h2>Manage Subject</h2>
			Menu name: <?php echo htmlentities($current_subject["menu_name"]); ?><br>

			Position: <?php echo $current_subject["position"]; ?><br>
			Visible: <?php echo ($current_subject["visible"] == 1) ? "Yes" : "No"; ?><br><br>


			<a href="edit_subject.php?subject=<?php echo urlencode($current_subject["id"]); ?>">Edit subject</a><br><br>
			<hr>
			<h2>Pages in this subject:</h2>
		
		<?php 
			echo "<ul>";
			$pages = find_pages_for_subject($current_subject["id"]);
			while($page = mysqli_fetch_assoc($pages)) {
				echo '<li><a href="manage_content.php?page=' . urlencode($page["id"]) . '">' . htmlentities($page["menu_name"]) . "</a></li>";
			}
			echo "</ul>";
		?>

			<a href="new_page.php?subject=<?php echo urlencode($current_subject["id"]); ?>">+ Add a new page to this subject</a>

		<?php } else if ($current_page) { ?>
			<h2>Manage Page</h2>
			Menu name: <?php echo htmlentities($current_page["menu_name"]); ?><br>
			Position: <?php echo $current_page["position"]; ?><br>
			Visible: <?php echo ($current_page["visible"] == 1) ? "Yes" : "No"; ?><br>
			Content:<br>
			<div class="view-content">
				<?php echo htmlentities($current_page["content"]); ?>
			</div>
			<a href="edit_page.php?page=<?php echo urlencode($current_page['id']); ?>">Edit page</a>
		<?php } else { ?>
			<p>Please select a subject or a page</p>
		<?php } ?>
	</div>
</div>
<?php include("../includes/layouts/footer.php"); ?>