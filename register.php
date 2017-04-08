<?php
require_once 'core/init.php';

if(Input::exists()) {
    $validate = new Validate();
    $validation = $validate->check($_POST, [
       'username' => [
           'required'   => true,
           'min'        => 2,
           'max'        => 20,
           'unique'     => 'users'
       ],
        'password' => [
            'required'  => true,
            'min'       => 6
        ],
        'password_again' => [
            'required'  => true,
            'matches'   => 'password'
        ],
        'name' => [
            'required'  => true,
            'min'       => 2,
            'max'       => 50
        ]
    ]);
    if($validation->passed()) {
        echo 'passed';
    } else {
        foreach($validation->errors() as $error) {
            echo $error . "<br>";
        }
    }
}

?>
<form action="" method="POST">
    <div class="field">
        <label for="username">Username</label>
        <input type="text" name="username" id="username" autocomplete="off" value="<?php echo Input::get('username') ?>">
    </div>
    <div class="field">
        <label for="password">Password</label>
        <input type="password" name="password" id="password" autocomplete="off">
    </div>
    <div class="field">
        <label for="password_again">Repeat Password</label>
        <input type="password" name="password_again" id="password_again" autocomplete="off">
    </div>
    <div class="field">
        <label for="name">Name</label>
        <input type="text" name="name" id="name" value="<?php echo Input::get('name') ?>">
    </div>
    <button type="submit">Register</button>
</form>