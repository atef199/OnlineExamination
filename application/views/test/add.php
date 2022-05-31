<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title"><?=$subjudul?></h3>
        <div class="box-tools pull-right">
            <a href="<?=base_url()?>test/master" class="btn btn-sm btn-flat btn-warning">
                <i class="fa fa-arrow-left"></i> Cancel
            </a>
        </div>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-sm-4">
                <div class="alert bg-purple">
                    <h4>Course <i class="fa fa-book pull-right"></i></h4>
                    <p><?=$course->course_name?></p>
                </div>
                <div class="alert bg-purple">
                    <h4>Lecturer <i class="fa fa-address-book-o pull-right"></i></h4>
                    <p><?=$lecturer->lecturer_name?></p>
                </div>
            </div>
            <div class="col-sm-4">
                <?=form_open('test/save', array('id'=>'formtest'), array('method'=>'add','lecturer_id'=>$lecturer->lecturer_id, 'course_id'=>$course->course_id))?>
                <div class="form-group">
                    <label for="exam_name">Exam Name</label>
                    <input autofocus="autofocus" onfocus="this.select()" placeholder="Exam Name" type="text" class="form-control" name="exam_name">
                    <small class="help-block"></small>
                </div>
                <div class="form-group">
                    <label for="questions_num">Number of Questions</label>
                    <input placeholder="Number of Questions" type="number" class="form-control" name="questions_num">
                    <small class="help-block"></small>
                </div>
                <div class="form-group">
                    <label for="start_date">Start Date</label>
                    <input name="start_date" type="text" class="datetimepicker form-control" placeholder="Start Date">
                    <small class="help-block"></small>
                </div>
                <div class="form-group">
                    <label for="end_date">Date of completion</label>
                    <input name="end_date" type="text" class="datetimepicker form-control" placeholder="Date of completion">
                    <small class="help-block"></small>
                </div>
                <div class="form-group">
                    <label for="time">Time</label>
                    <input placeholder="In Minute" type="number" class="form-control" name="time">
                    <small class="help-block"></small>
                </div>
                <div class="form-group">
                    <label for="exam_type">Question Pattern</label>
                    <select name="exam_type" class="form-control">
                        <option value="" disabled selected>--- Choose ---</option>
                        <option value="Random">Randomized Question</option>
                        <option value="Sort">Sorted questions</option>
                    </select>
                    <small class="help-block"></small>
                </div>
                <div class="form-group pull-right">
                    <button type="reset" class="btn btn-default btn-flat">
                        <i class="fa fa-rotate-left"></i> Reset
                    </button>
                    <button id="submit" type="submit" class="btn btn-flat bg-purple"><i class="fa fa-save"></i> Save</button>
                </div>
                <?=form_close()?>
            </div>
        </div>
    </div>
</div>

<script src="<?=base_url()?>assets/dist/js/app/test/add.js"></script>