<div class="callout callout-info">
    <h4>Exam Rules!</h4>
    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Maxime minus dolores accusantium fugiat debitis modi voluptates non consequuntur nemo expedita nihil laudantium commodi voluptatum voluptatem molestiae consectetur incidunt animi, qui exercitationem? Nisi illo, magnam perferendis commodi consequuntur impedit, et nihil excepturi quas iste cum sunt debitis odio beatae placeat nemo..</p>
</div>
<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">Confirm Data</h3>
    </div>
    <div class="box-body">
        <span id="exam_id" data-key="<?=$encrypted_id?>"></span>
        <div class="row">
            <div class="col-sm-6">
                <table class="table table-bordered">
                    <tr>
                        <th>Name</th>
                        <td><?=$mhs->nama?></td>
                    </tr>
                    <tr>
                        <th>Lecturer</th>
                        <td><?=$test->lecturer_name?></td>
                    </tr>
                    <tr>
                        <th>Class/Department</th>
                        <td><?=$mhs->level_name?> / <?=$mhs->dep_name?></td>
                    </tr>
                    <tr>
                        <th>Exam Name</th>
                        <td><?=$test->exam_name?></td>
                    </tr>
                    <tr>
                        <th>Number of Questions</th>
                        <td><?=$test->questions_num?></td>
                    </tr>
                    <tr>
                        <th>Time</th>
                        <td><?=$test->time?> Minute</td>
                    </tr>
                    <tr>
                        <th>Late</th>
                        <td>
                            <?=strftime('%d %B %Y', strtotime($test->end_date))?> 
                            <?=date('H:i:s', strtotime($test->end_date))?>
                        </td>
                    </tr>
                    <tr>
                        <th style="vertical-align:middle">Token</th>
                        <td>
                            <input autocomplete="off" id="token" placeholder="Token" type="text" class="input-sm form-control">
                        </td>
                    </tr>
                </table>
            </div>
            <div class="col-sm-6">
                <div class="box box-solid">
                    <div class="box-body pb-0">
                        <div class="callout callout-info">
                            <p>
                            The time to take the exam is when the "START" button is green.
                            </p>
                        </div>
                        <?php
                        $mulai = strtotime($test->start_date);
                        $end_date = strtotime($test->end_date);
                        $now = time();
                        if($mulai > $now) : 
                        ?>
                        <div class="callout callout-success">
                            <strong><i class="fa fa-clock-o"></i> The exam will start on</strong>
                            <br>
                            <span class="countdown" data-time="<?=date('Y-m-d H:i:s', strtotime($test->start_date))?>">00 Days, 00 Hours, 00 Minutes, 00 Seconds</strong><br/>
                        </div>
                        <?php elseif( $end_date > $now ) : ?>
                        <button id="btncek" data-id="<?=$test->exam_id?>" class="btn btn-success btn-lg mb-4">
                            <i class="fa fa-pencil"></i> Start
                        </button>
                        <div class="callout callout-danger">
                            <i class="fa fa-clock-o"></i> <strong class="countdown" data-time="<?=date('Y-m-d H:i:s', strtotime($test->end_date))?>">00 Days, 00 Hours, 00 Minutes, 00 Seconds</strong><br/>
                            Timeout of pressing the start button.
                        </div>
                        <?php else : ?>
                        <div class="callout callout-danger">
                        The time to press the <strong>"START"</strong> button is up.<br/>
                        Please contact your lecturer to be able to take the SUBSTITUTE exam.
                        </div>
                        <?php endif;?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?=base_url()?>assets/dist/js/app/test/token.js"></script>