<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">Form <?=$judul?></h3>
        <div class="box-tools pull-right">
            <a href="<?=base_url()?>departmentcourse" class="btn btn-warning btn-flat btn-sm">
                <i class="fa fa-arrow-left"></i> Cancel
            </a>
        </div>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-sm-4 col-sm-offset-4">
                    <?=form_open('departmentcourse/save', array('id'=>'departmentcourse'), array('method'=>'edit', 'course_id'=>$course_id))?>
                <div class="form-group">
                    <label>Course</label>
                    <input type="text" readonly="readonly" value="<?=$course->course_name?>" class="form-control">
                    <small class="help-block text-right"></small>
                </div>
                <div class="form-group">
                    <label>Department</label>
                    <select id="department" multiple="multiple" name="dep_id[]" class="form-control select2" style="width: 100%!important">
                        <?php 
                        $sj = [];
                        foreach ($department as $key => $val) {
                            $sj[] = $val->dep_id;
                        }
                        foreach ($all_department as $m) : ?>
                            <option <?=in_array($m->dep_id, $sj) ? "selected" : "" ?> value="<?=$m->dep_id?>"><?=$m->dep_name?></option>
                        <?php endforeach; ?>
                    </select>
                    <small class="help-block text-right"></small>
                </div>
                <div class="form-group pull-right">
                    <button type="reset" class="btn btn-flat btn-default">
                        <i class="fa fa-rotate-left"></i> Reset
                    </button>
                    <button id="submit" type="submit" class="btn btn-flat bg-purple">
                        <i class="fa fa-save"></i> Save
                    </button>
                </div>
                <?=form_close()?>
            </div>
        </div>
    </div>
</div>

<script src="<?=base_url()?>assets/dist/js/app/relation/departmentcourse/edit.js"></script>