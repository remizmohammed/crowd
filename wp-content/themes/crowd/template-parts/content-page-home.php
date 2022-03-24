<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package crowd
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

    <?php
        $message = "";
        $messageType = "";
        $success = false;
        if( isset( $_POST['submit'] ) ) {
            global $wpdb;
	        $submissionsTable = $wpdb->prefix.'submissions';

            $submissions = $wpdb->get_results( "SELECT * FROM $submissionsTable WHERE pemail = '".$_POST['pemail']."'");
            if( count($submissions) >= 1 ) {
                $message = "Email already exists";
                $messageType = "alert-error";
            }

            if( count($submissions) == 0 ) {
                $wpdb->insert( $submissionsTable, array(
                    'pname' => $_POST['pname'],
                    'pemail' => $_POST['pemail'],
                ));
                $message = "Successfully submitted";
                $messageType = "alert-success";
            }
        }
    ?>
    <?php if( $message ) : ?>
        <div class="<?php echo $messageType;?>">
            <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
            <?php echo $message;?>
        </div>
    <?php endif; ?>
    
    <?php if( getCrowdSubmissionsRemainingCount() > 0 ):?>
        <form action="" method="POST">
            <label for="pname">Name:</label><br>
            <input type="text" id="pname" name="pname" value="" required><br>
            <label for="pemail">Email:</label><br>
            <input type="email" id="pemail" name="pemail" value="" required><br><br>
            <input type="submit" name="submit" value="Submit">
            <p> <?php echo getCrowdSubmissionsRemainingCount(). ' submissions remaining'?></p>
        </form> 
    <?php else: ?>
        <p>The form is fully booked</p>
    <?php endif; ?>
</article><!-- #post-<?php the_ID(); ?> -->
