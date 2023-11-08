<?php echo form_open(current_url(), array('id'=>"form-simpan",'class' => 'form-horizontal col-sm-12 col-md-12 col-lg-12')); ?>
<input type="hidden" name="kode" id="kode" disabled="true" />
<style>
    .col-sm-8{
        padding-right: 0px;
    }
</style>
<div class="col-lg-12">
    <div id="group-kode" class="form-group">
        <label class="form-label col-sm-8" >Kode SIA</label>
        <div class="col-sm-12">
            <?php echo form_input('prodiKode','',array("id"=>"prodiKode",'class'=>'form-control','placeholderr'=>'Kode','disabled'=>'')); ?>
        </div>
    </div>
</div>
<div class="col-lg-12">
    <div id="group-nama" class="form-group">
        <label class="form-label col-sm-8" >Nama Prodi SIA</label>
        <div class="col-sm-12">
            <?php echo form_input('prodiNamaResmi','',array("id"=>"prodiNamaResmi",'class'=>'form-control','placeholderr'=>'Nama Jalur','disabled'=>'')); ?>
        </div>
    </div>
</div>
<div class="col-lg-12">
    <div id="group-nama" class="form-group">
        <label class="form-label col-sm-8" >Kode NIM</label>
        <div class="col-sm-12">
            <?php echo form_input('prodiId','',array("id"=>"prodiId",'class'=>'form-control','placeholderr'=>'Kode prodi untuk NIM')); ?>
        </div>
    </div>
</div>
<?php echo form_close(); ?>
