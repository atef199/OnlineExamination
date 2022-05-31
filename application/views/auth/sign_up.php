<!DOCTYPE html>
<html>

<head>

    <!-- Meta Tag -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Student Registration</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    
    <!-- Required CSS -->
    <link rel="stylesheet" href="<?=base_url()?>assets/bower_components/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?=base_url()?>assets/bower_components/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?=base_url()?>assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="<?=base_url()?>assets/bower_components/select2/css/select2.min.css">
    <link rel="stylesheet" href="<?=base_url()?>assets/dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="<?=base_url()?>assets/dist/css/skins/skin-blue.min.css">
    <link rel="stylesheet" href="<?=base_url()?>assets/dist/css/skins/skin-yellow.min.css">
    <link rel="stylesheet" href="<?=base_url()?>assets/bower_components/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css">
    <link rel="stylesheet" href="<?=base_url()?>assets/bower_components/pace/pace-theme-flash.css">
    
    <!-- Datatables Buttons -->
    <link rel="stylesheet" href="<?=base_url()?>assets/bower_components/datatables.net-bs/plugins/Buttons-1.5.6/css/buttons.bootstrap.min.css">

    <!-- textarea editor -->
    <link rel="stylesheet" href="<?=base_url()?>assets/bower_components/codemirror/lib/codemirror.min.css">
    <link rel="stylesheet" href="<?=base_url()?>assets/bower_components/froala_editor/css/froala_editor.pkgd.min.css">
    <link rel="stylesheet" href="<?=base_url()?>assets/bower_components/froala_editor/css/froala_style.min.css">
    <link rel="stylesheet" href="<?=base_url()?>assets/bower_components/froala_editor/css/themes/royal.min.css">
    <!-- /texarea editor; -->

    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?=base_url()?>assets/dist/css/mystyle.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->

    <!-- include summernote css/js -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
</head>

<!-- Must Load First -->
<script src="<?=base_url()?>assets/bower_components/jquery/jquery-3.3.1.min.js"></script>
<script src="<?=base_url()?>assets/bower_components/sweetalert2/sweetalert2.all.min.js"></script>
<script src="<?=base_url()?>assets/bower_components/select2/js/select2.full.min.js"></script>
<script src="<?=base_url()?>assets/bower_components/moment/min/moment.min.js"></script>
<script src="<?=base_url()?>assets/bower_components/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js"></script>     

<script type="text/javascript">
    let base_url = '<?=base_url()?>';
</script>
<div class="box">
    <div class="box-header with-border">
        <h3 style="text-align: center;">Student Registration</h3>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-sm-4 col-sm-offset-4">
                <?=form_open('SignUp/save', array('id'=>'student'), array('method'=>'add'))?>
                    <div class="form-group">
                        <label for="nim">PID</label>
                        <input autofocus="autofocus" onfocus="this.select()" placeholder="PID" type="text" name="nim" class="form-control">
                        <small class="help-block"></small>
                    </div>
                    <div class="form-group">
                        <label for="nama">Name</label>
                        <input placeholder="Student's Name" type="text" name="nama" class="form-control">
                        <small class="help-block"></small>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input placeholder="Email" type="email" name="email" class="form-control">
                        <small class="help-block"></small>
                    </div>
                    <div class="form-group">
                        <label for="jenis_kelamin">Gender</label>
                        <select name="jenis_kelamin" class="form-control select2">
                            <option value="">-- Choose --</option>
                            <option value="M">Male</option>
                            <option value="F">Female</option>
                        </select>
                        <small class="help-block"></small>
                    </div>
                    <div class="form-group">
                        <label for="department">Department</label>
                        <select id="department" name="department" class="form-control select2">
                            <option value="" disabled selected>-- Choose --</option>
                        </select>
                        <small class="help-block"></small>
                    </div>
                    <div class="form-group">
                        <label for="level">Class</label>
                        <select id="level" name="level" class="form-control select2">
                            <option value="">-- Choose --</option>
                        </select>
                        <small class="help-block"></small>
                    </div>
                    <a href="<?=base_url()?>login" class="btn btn-info btn-lg"><span class="glyphicon glyphicon-chevron-left"></span> Login</a>
                  <!--  <button  onclick="location.href='<?=base_url()?>login'" class="btn btn-flat bg-green" style="float: left;"><i class="fa fa-save"></i> Login</button>-->
                    <div class="form-group pull-right">
                        <button type="reset" class="btn btn-flat btn-default" style="width: 95px !important;height: 44px !important;"><i class="fa fa-rotate-left"></i> Reset</button>
                        <button type="submit" id="submit"style="width: 95px !important;height: 44px !important;" class="btn btn-flat bg-purple"><i class="fa fa-save"></i> Register</button>
                    </div>
                <?=form_close()?>
            </div>
        </div>
    </div>
</div>

            <script type="text/javascript">

                function ajaxcsrf() {
                    var csrfname = '<?= $this->security->get_csrf_token_name() ?>';
                    var csrfhash = '<?= $this->security->get_csrf_hash() ?>';
                    var csrf = {};
                    csrf[csrfname] = csrfhash;
                    $.ajaxSetup({
                        "data": csrf
                    });
                }

                function reload_ajax() {
                    table.ajax.reload(null, false);
                }
                var sign_up = true;
            </script>
<script src="<?=base_url()?>assets/dist/js/app/master/student/add.js"></script>
