<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">Form <?=$judul?></h3>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
        </div>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-sm-offset-3 col-sm-6">
                <div class="my-2">
                    <div class="form-horizontal form-inline">
                        <a href="<?=base_url('level')?>" class="btn btn-default btn-xs">
                            <i class="fa fa-arrow-left"></i> Cancel
                        </a>
                        <div class="pull-right">
                            <span> Amount : </span><label for=""><?=count($level)?></label>
                        </div>
                    </div>
                </div>
                <?=form_open('level/save', array('id'=>'level'), array('mode'=>'edit'))?>
                <table id="form-table" class="table text-center table-condensed">
                    <thead>
                        <tr>
                            <th># No</th>
                            <th>Level</th>
                            <th>Dept.</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $i = 1;
                        foreach($level as $row) : ?> 
                            <tr>
                                <td><?=$i?></td>
                                <td>
                                    <div class="form-group">
                                        <?=form_hidden('level_id['.$i.']', $row->level_id);?>
                                        <input required="required" autofocus="autofocus" onfocus="this.select()" value="<?=$row->level_name?>" type="text" name="level_name[<?=$i?>]" class="form-control">
                                        <span class="d-none">DON'T DELETE THIS</span>
                                        <small class="help-block text-right"></small>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <select required="required" name="dep_id[<?=$i?>]" class="input-sm form-control select2" style="width: 100%!important">
                                            <option value="" disabled>-- Choose --</option>
                                            <?php foreach ($department as $j) : ?>
                                                <option <?= $row->dep_id == $j->dep_id ? "selected='selected'" : "" ?> value="<?=$j->dep_id?>"><?=$j->dep_name?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <small class="help-block text-right"></small>
                                    </div>
                                </td>
                            </tr>
                        <?php $i++;endforeach; ?>
                    </tbody>
                </table>
                <button id="submit"  type="submit" class="mb-4 btn btn-block btn-flat bg-purple">
                    <i class="fa fa-edit"></i> Save Changes
                </button>
                <?=form_close()?>
            </div>
        </div>
    </div>
</div>

<script src="<?=base_url()?>assets/dist/js/app/master/level/edit.js"></script>