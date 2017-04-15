<?php
require_once 'core/init.php';
// if session is set, output the flash message
if(Session::exists('home')) {
    echo Session::flash('home');
}
if(Session::exists('login')) {
    echo Session::flash('login');
}
$user = new User();
if($user->isLoggedIn()) {
    ?>
    <p>Hello <a href="profile.php?user=<?php echo $user->data()->username ?>"><?php echo $user->data()->username; ?></a></p>
    <ul>
        <li><a href="logout.php">Log out</a></li>
        <li><a href="update.php">Update details</a></li>
        <li><a href="changepassword.php">Change password</a></li>
    </ul>
    <?php
//    var_dump($user->hasPermission());
    if ($user->hasPermission('admin')) {
        echo 'You are an administrator';
    }
} else {
    ?>
    <p>You need to login <a href="login.php">here</a></p>
    <?php
}