<?php
echo 'session id'. var_dump($_SESSION);
if ( ! isset( $_SESSION['user_id'] ) ) {
    wp_redirect( home_url( '/login' ) );
    exit;
}
$user_id = $_SESSION['user_id'];
$user_username = $_SESSION['user_username'];
?>

<h2>Welcome <?php echo $user_username; ?></h2>
<p>Your user ID is <?php echo $user_id; ?></p>
