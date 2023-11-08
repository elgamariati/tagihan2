<?php echo form_open(current_url(), array('id'=>"form-simpan",'class' => 'form-horizontal')); ?>
<input type="hidden" name="kode" id="kode" disabled="true" />
<style>
    .col-sm-8{
        padding-right: 0px;
    }
</style>
<div class="col-lg-6">
    <div id="group-kode" class="form-group">
        <label class="form-label col-sm-8" >Role</label>
        <div class="col-sm-12">
            <?php echo form_input('role','',array("id"=>"role",'class'=>'form-control')); ?>
        </div>
    </div>
</div>
<?php echo form_close(); ?>
