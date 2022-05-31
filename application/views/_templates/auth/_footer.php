<script src="<?=base_url()?>assets/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="<?=base_url()?>assets/plugins/iCheck/icheck.min.js"></script>
<script>
    $(function () {
        $('input').iCheck({
        checkboxClass: 'icheckbox_square-blue',
        radioClass: 'iradio_square-blue',
        increaseArea: '20%' /* optional */
        });
    });
    $("#remember").attr("style","position: absolute;top: -20%;left: -20%;display: block;width: 115%;height: 120%;margin: 0px;padding: 0px;background: rgb(255, 255, 255);border: 0px;opacity: 60;");
</script>
</body>
</html>