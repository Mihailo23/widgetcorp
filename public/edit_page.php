<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php require_once("../includes/validation_functions.php"); ?>

<?php find_selected_page(); ?>

<?php
    if (!$current_page) {
        // subject ID was missing or invalid or
        // subject couldn't be found in database
        redirect_to("manage_content.php");
    }
?>

<?php 
    if (isset($_POST["submit"])) {
        // Process the form

        // validations
        $required_fields = array("menu_name", "position", "visible");
        validate_presences($required_fields);
        
        $fields_with_max_lenghts = array("menu_name" => 30);
        validate_max_lengths($fields_with_max_lenghts);

        if (empty($errors)) {

            // Perform update
            $id = $current_subject["id"];
            $menu_name = mysql_prep($_POST["menu_name"]);
            $position = (int) $_POST["position"];
            $visible = (int) $_POST["visible"];

            $query  = "UPDATE subjects SET ";
            $query .= "menu_name = '{$menu_name}', ";
            $query .= "position = {$position}, ";
            $query .= "visible = {$visible} ";
            $query .= "WHERE id = {$id} ";
            $query .= "LIMIT 1";

            $result = mysqli_query($connection, $query);

            if ($result && mysqli_affected_rows($connection) >= 0) { // if mysqli_affected_rows fail it will return -1
                //  Success
                $_SESSION["message"] = "Subject updated.";
                redirect_to("manage_content.php");
            } else {
                //  Failure
                $message = "Subject update failed.";
                redirect_to("new_subject.php");
            }
        }

    } else {
        // This is probably a GET request
    }   // end if (isset($_POST["submit"]))
?>

<?php include("../includes/layouts/header.php"); ?>

    <div id="main">
        <div id="navigation">
            <?php echo navigation($current_subject, $current_page); ?>
        </div>
        <div id="page">
            <?php // $message is just a variable, no need for $_SESSION
                if (!empty($message)) {
                    echo "<div class=\"message\">" . htmlentities($message) . "</div>";
                }
            ?>
            <?php echo form_errors($errors); ?>
            <h2>Edit Subject: <?php echo htmlentities($current_page["menu_name"]); ?></h2>

            <form action="edit_subject.php?subject=<?php echo urlencode($current_page["id"]); ?>" method="post">
                <p>Menu name:
                    <input type="text" name="menu_name" value="<?php echo htmlentities($current_page["menu_name"]); ?>" />
                </p>
                <p>Position:
                    <select name="position">
                        <?php
                        $subject_set = find_all_subjects();
                        $subject_count = mysqli_num_rows($subject_set);
                        for ($count=1; $count <= $subject_count; $count++) {
                            echo "<option value=\"{$count}\"";
                            if ($current_page["position"] == $count) {
                                echo " selected";
                            }
                            echo ">{$count}</option>";
                        }
                        ?>
                    </select>
                </p>
                <p>Visible:
                    <input type="radio" name="visible" value="0" <?php echo ($current_page["visible"] == 0) ? "checked" : "" ; ?> /> No
                    &nbsp;
                    <input type="radio" name="visible" value="1" <?php echo ($current_page["visible"] == 1) ? "checked" : "" ; ?>/> Yes
                </p>
                <input type="submit" name="submit" value="Edit Subject" />
            </form>
            <br />
            <a href="manage_content.php">Cancel</a>
            &nbsp;
            &nbsp;
            <a href="delete_subject.php?subject=<?php echo urlencode($current_subject["id"]); ?>" onclick="return confirm('Are you sure?');">Delete subject</a>
        </div>
    </div>
<?php include("../includes/layouts/footer.php"); ?>