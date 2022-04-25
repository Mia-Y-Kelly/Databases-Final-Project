<?php
    require "db.php";
    session_start();
/*    echo "<pre>";
    print_r($_SESSION);
    print_r($_POST);
    echo "</pre>";
*/
	// User clicked the submit button
	if(isset($_POST['submit'])) {
		$_SESSION['username'] = $_POST['username'];
		resetPwd($_POST['username'], $_POST['new_pwd'], $_POST['confirm_new_pwd']);
		return;	
	}
?>


<html>
   	 <link rel="stylesheet" href="resetpwd.css"></link>
	 <body>
        <h1>Please change your password</h1>
        <form class="createPwd" action="resetpwd.php" method="POST">
			<div class="innerPwd">
				<label for="username" class="label" id="username"><b>Username</b></label><br/>
				<input type="text" id="username" name="username" value="" require><br/>
				<label for="new_pwd" class="label"><b>New Password</b></label><br>
				<input type="text" id="new_pwd" name="new_pwd" class="text" value="" require>
				<br/>
				<label for="confirm_new_pwd" class="label"><b>Confirm Password</b></label><br>
				<input type="text" id=confirm_new_pwd name="confirm_new_pwd" class="text" value="" require>
				<br><br>
				<input type="submit" id="submit" name="submit" value="submit">
		</div>
	 </form>
    </body>
</html>
