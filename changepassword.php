<?php
require_once 'core/init.php';

$user = new User();

if(!$user->isLoggedIn()) {
    Redirect::to('index.php');
}
if(Input::exists()) {
    if(Token::check(Input::get('token'))) {

        $validate = new Validate();
        $validation = $validate->check($_POST, [
            'password_current'  => [
                'required'  => true,
                'min'       => 6
            ],
            'password_new'  => [
                'required'  => true,
                'min'       => 6
            ],
            'password_new_again'  => [
                'required'  => true,
                'min'       => 6,
                'matches'   => 'password_new'
            ]
        ]);

        if($validation->passed()) {
            if(Hash::verify(Input::get('password_current'), $user->data()->password) == 1) {
                $user->update([
                    'password' => Hash::make(Input::get('password_new'))
                ]);
                Session::flash('home', 'You\'r password has been changed');
                Redirect::to('index.php');
            } else {
                echo 'You\' current password is wrong';
            }
        } else {
            foreach($validation->errors() as $error) {
                echo $error, "<br>";
            }
        }
    }
}
?>
<form action="" method="post">
    <div class="field">
        <label for="password_current">Current password</label>
        <input type="password" id="password_current" name="password_current">
    </div>
    <div class="field">
        <label for="password_new">New password</label>
        <input type="password" id="password_new" name="password_new">
    </div>
    <div class="field">
        <label for="password_new_again">New password</label>
        <input type="password" id="password_new_again" name="password_new_again">
    </div>
    <input type="hidden" name="token" value="<?php echo Token::generate() ?>">
    <button type="submit">Update password</button>
</form>