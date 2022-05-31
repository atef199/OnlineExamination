<div class="login-box pt-5">
	<!-- /.login-logo -->
	<div class="login-box-body">
	<h3 class="text-center mt-0 mb-4">
		<b>O</b>nline <b>E</b>xamination <b>S</b>ystem
	</h3> 
	<p class="login-box-msg">Login to start your session</p>

	<div id="infoMessage" class="text-center"><?php echo $message;?></div>

	<?= form_open("auth/cek_login", array('id'=>'login'));?>
		<div class="form-group has-feedback">
			<?= form_input($identity);?>
			<span class="fa fa-envelope form-control-feedback"></span>
			<span class="help-block"></span>
		</div>
		<div class="form-group has-feedback">
			<?= form_input($password);?>
			<span class="glyphicon glyphicon-lock form-control-feedback"></span>
			<span class="help-block"></span>
		</div>
		<div class="row">
			<div class="col-xs-8">
			<div class="checkbox icheck">
				<label>
				<?= form_checkbox('remember', '', FALSE, array("id"=>"remember","style"=>"position: absolute;top: -20% !important;left: -20% !important;display: block;width: 115% !important;height: 120% !important;margin: 0px;padding: 0px;background: rgb(255, 255, 255);border: 0px;opacity: 60 !important;"));?> &emsp;&emsp;Remember Me
				</label>
			</div>
			</div>
			<!-- /.col -->
			<div class="col-xs-4">
			<?= form_submit('submit', lang('login_submit_btn'), array('id'=>'submit','class'=>'btn btn-success btn-block btn-flat'));?>
			</div>
			<!-- /.col -->
		</div>
		<?= form_close(); ?>

		<a href="<?=base_url()?>auth/forgot_password" class="text-center"><?= lang('login_forgot_password');?></a>
		<a href="<?=base_url()?>SignUp" style="float: right;" t="">Register</a>
	</div>
</div>
<div class="col-md-4 col-md-offset-4 marg" style="margin-top: -48px;">
    	<nav class="navbar navbar-default" style="
    height: 62px;
">
    	  <div class="container-fluid">
    	    <div class="navbar-header">
    	      <a class="navbar-brand" href="#">
    	       &nbsp;  For Quick Demo Login Click Below...
    	      </a>
    	    </div>
    	  </div>
    	</nav>
    	</div>
    	<br><br>
<center>
    	        <div class="btn-group" role="group" aria-label="...">
    	            <button class="btn btn-sm btn-primary" id="admin">Admin</button>
    	            <button class="btn btn-sm btn-info" id="teacher">Teacher</button>
    	            <button class="btn btn-sm btn-warning" id="student">Student</button>
    	        </div>
    	    </center>
<script type="text/javascript">
	let base_url = '<?=base_url();?>';
</script>
<script src="<?=base_url()?>assets/dist/js/app/auth/login.js"></script>