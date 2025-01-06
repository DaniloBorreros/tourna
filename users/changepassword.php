<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title></title>

	<link href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
	<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
	<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">


	<style type="text/css">
		.input-wrapper { 
            position: relative; 
        }
  
        .input-wrapper span { 
            position: absolute; 
            top: 15px; 
            right: 15px; 
            border: none; 
            background-color: transparent; 
            cursor: pointer; 
        } 
	</style>

</head>
<body>


	<!------ Include the above in your HEAD tag ---------->

<div class="container">
	<div class="row">			
		<div class="col-sm-12">
			<div class="col-md-12" style="width: 50%; margin-top: 5%; margin-left: 25%;">
		        <?php
		        session_start();
		        if (isset($_SESSION['type']) && $_SESSION['type'] == 'success') {
		        ?>
		            <div class="alert alert-success" role="alert">
		                <?php echo $_SESSION['msg']; ?>
		            </div>
		            <?php
		        }
		        elseif (isset($_SESSION['type']) && $_SESSION['type'] == 'danger') {
		        ?>
		            <div class="alert alert-danger" role="alert">
		                <?php echo $_SESSION['msg']; ?>
		            </div>
		            <?php
		        }
		        unset($_SESSION['type']);
		        unset($_SESSION['msg']);
		        ?>
	    	</div>

	    	<div class="col-md-12">
	    		<h1 style="text-align: center;">Change Password</h1>
	    	</div>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-6 col-sm-offset-3">
			<p class="text-center">Use the form below to change your current password.</p>
			<form method="post" id="passwordForm" action="changepass.php">
				<div class="form-group input-wrapper">
				    <input type="password" class="input-lg form-control" name="oldpassword" id="oldpassword" placeholder="Old Password" autocomplete="off" required>
				    <span toggle="#oldpassword" class="fa fa-fw fa-eye field-icon toggle-password"></span>
				</div>
				<div class="form-group input-wrapper">
			        <input type="password" class="input-lg form-control" name="password" id="password1" placeholder="New Password" autocomplete="off" required>
			        <span toggle="#password1" class="fa fa-fw fa-eye field-icon toggle-password"></span>
			    </div>
				<div class="row">
					<div class="col-sm-6">
						<span id="8char" class="glyphicon glyphicon-remove" style="color:#FF0004;"></span> 8 Characters Long<br>
						<span id="ucase" class="glyphicon glyphicon-remove" style="color:#FF0004;"></span> One Uppercase Letter
					</div>
					<div class="col-sm-6">
						<span id="lcase" class="glyphicon glyphicon-remove" style="color:#FF0004;"></span> One Lowercase Letter<br>
						<span id="num" class="glyphicon glyphicon-remove" style="color:#FF0004;"></span> One Number
					</div>
				</div>
				<div class="form-group input-wrapper">
				    <input type="password" class="input-lg form-control" name="confirmpassword" id="confirmpassword" placeholder="Confirm Password" autocomplete="off" required>
				    <span toggle="#confirmpassword" class="fa fa-fw fa-eye field-icon toggle-password"></span>
				</div>
				<div class="row">
					<div class="col-sm-12">
						<span id="pwmatch" class="glyphicon glyphicon-remove" style="color:#FF0004;"></span> Passwords Match
					</div>
				</div>
				<input type="submit" class="col-xs-12 btn btn-primary btn-load btn-lg" name="changepassword" data-loading-text="Changing Password..." value="Change Password">
			</form>
		</div><!--/col-sm-6-->
	</div><!--/row-->
</div>


<script type="text/javascript" src="changepassword.js"></script>



</body>
</html>



