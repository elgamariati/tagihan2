<?php echo form_open(current_url(), array('id' => "form-simpan", 'class' => 'form-horizontal'));
$listRole = ['UKT', 'IPI']; ?>
<input type="hidden" name="kode" id="kode" disabled="true" />
<style>
    .col-sm-8 {
        padding-right: 0px;
    }
</style>
<div class="col-lg-12">
    <div id="group-kode" class="form-group">
        <label class="form-label col-sm-8">Username</label>
        <div class="col-sm-12">
            <?php echo form_input('username', '', array("id" => "username", 'class' => 'form-control')); ?>
        </div>
    </div>
</div>
<div class="col-lg-12">
    <div id="group-password" class="form-group">
        <label class="form-label col-sm-8">Password</label>
        <div class="col-sm-12">
            <?php echo form_password('password', '', array("id" => "password", 'class' => 'form-control')); ?>
        </div>
    </div>
</div>
<div class="col-lg-12">
    <div id="group-ulangpassword" class="form-group">
        <label class="form-label col-sm-8">Ulangi Password</label>
        <div class="col-sm-12">
            <?php echo form_password('ulangPassword', '', array("id" => "ulangPassword", 'class' => 'form-control')); ?>
        </div>
    </div>
</div>
<div class="col-lg-12">
    <div id="group-nama" class="form-group">
        <label class="form-label col-sm-8">Nama</label>
        <div class="col-sm-12">
            <?php echo form_input('nama', '', array("id" => "nama", 'class' => 'form-control')); ?>
        </div>
    </div>
</div>
<div class="col-lg-12">
    <div id="group-nama" class="form-group">
        <label class="form-label col-sm-8">Jenis Cicil</label>
        <div class="col-sm-12">
            <?php echo form_dropdown('jenis_cicil', $listRole, '', array("id" => "role", 'class' => 'form-control multi round')); ?>
        </div>
    </div>
</div>
<?php echo form_close(); ?>