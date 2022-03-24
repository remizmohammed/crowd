<style>
    table {
    font-family: arial, sans-serif;
    border-collapse: collapse;
    width: 100%;
    }

    td, th {
    border: 1px solid #dddddd;
    text-align: left;
    padding: 8px;
    }

    tr:nth-child(even) {
    background-color: #dddddd;
    }
    .submission-form-wrap {
        margin-bottom: 20px;
    }
</style>

<h2>
    Crowd Submissions
</h2>
<?php
    global $wpdb;
    global $wp;
    $submissionsTable = $wpdb->prefix.'submissions';
    $current_page_url = admin_url( "admin.php?page=".$_GET["page"] );

    /* delete submission entry */
    if(isset( $_GET["delete"] )) {
        $wpdb->delete( $submissionsTable, array( 'id' => $_GET["delete"] ) );
    }

    if( isset( $_POST["save"] ) ) {
        update_option( 'crowd_submissions_limit', $_POST["maxsubmission"] );
    }

    /* Get submissions */
    $submissions = $wpdb->get_results( "SELECT * FROM $submissionsTable");
?>
<div class="submission-form-wrap">
<form action="" method="POST">
        <label for="maxsubmission">Set Maximum Submission Limit:</label> 
        <input type="number" id="maxsubmission" name="maxsubmission" value="<?php echo (get_option( 'crowd_submissions_limit', false))? get_option( 'crowd_submissions_limit', false): 10 ?>" required min="1"> 
        <input type="submit" name="save" value="Save">
</form>
</div>

<div class="submission-list-wrap">
    <table>
    <tr>
        <th>No.</th>
        <th>Name</th>
        <th>Email</th>
        <th>Actions</th>
    </tr>
    <?php foreach($submissions as $index => $submission){ ?>
        <tr>
            <td><?php echo $index + 1?></td>
            <td><?php echo $submission->pname?></td>
            <td><?php echo $submission->pemail?></td>
            <td><a href="<?php echo $current_page_url?>&delete=<?php echo $submission->id?>">Delete</a></td>
        </tr>
    <?php } ?>
    <?php 
        if( empty( $submissions ) ): ?>
            <tr>
                <td colspan="4">Empty Submissions</td>
            </tr>
        <?php
        endif;
    ?>
    </table>
</div>
