<?php
require_once 'core/init.php';
// if request is set go forward
if(Input::exists()) {
    // if token exists for csrf protection
    if(Token::check(Input::get('token'))) {
        // validate the fields that user submitted
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
        // if all info passed the validation, insert into db
        if($validation->passed()) {
            $user = new User();

            // try to insert the user in db
            try {
                $user->create([
                    'username'  => Input::get('username'),
                    'password'  => Hash::make(Input::get('password')),
                    'name'      => Input::get('name'),
                    'joined'    => date('Y-m-d H:i:s'),
                    'groupNum'     => 1
                ]);
                // flash the message to the user
                Session::flash('home','You have been registered and can now login');
                // and redirect to home page
                Redirect::to('index.php');
                //else show error
            } catch(Exception $e){
                die($e->getMessage());
            }
        } else {
            foreach($validation->errors() as $error) {
                echo $error . "<br>";
            }
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
    <input type="hidden" name="token" value="<?php echo Token::generate() ?>">
    <button type="submit">Register</button>
</form>