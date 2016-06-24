<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>

<?php confirm_logged_in(); ?>

<?php
  $current_admin = find_admin_by_id($_GET["id"]);
  if (!$current_admin) {
    // page ID was missing or invalid or 
    // page couldn't be found in database
    redirect_to("manage_admins.php");
  }
  
  $id = $current_admin["id"];
  $query = "DELETE FROM admins WHERE id = {$id} LIMIT 1";
  $result = mysqli_query($connection, $query);

  if ($result && mysqli_affected_rows($connection) == 1) {
    // Success
    $_SESSION["message"] = "Admin deleted.";
    redirect_to("manage_admins.php");
  } else {
    // Failure
    $_SESSION["message"] = "Admin deletion failed.";
    redirect_to("manage_admins.php");
  }
  
?>
