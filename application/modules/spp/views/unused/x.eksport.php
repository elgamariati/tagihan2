<?php
echo form_open(current_url(), array('id'=>"form-simpan",'class' => 'form-horizontal'));
?>
<style>
    .round{
        border-radius: 2px;
        height: 38px !important;
    }
</style>

<div class="col-lg-12 top-modal-space" style="margin-left: 1px; margin-top: 0px;">
    <div id="group-nama" class="form-group">
        <label class="form-label col-sm-8" >Tahun</label>
        <div class="col-sm-12">
            <?php echo form_dropdown('tahun',$tahun,$this->session->user['tahun'], array("id" => "tahun", "class" => 'chosen-select form-control drop_select')); ?>
        </div>
    </div>
    <div class="col-sm-1">
        <span style="display: none" class="fa fa-2x fa-spinner fa-spin"></span>
    </div>
</div>

<div class="col-lg-12 top-modal-space" style="margin-left: 1px; margin-top: 0px;">
    <div id="group-nama" class="form-group">
        <label class="form-label col-sm-8" >Jalur Masuk</label>
        <div class="col-sm-12">
            <?php echo form_dropdown('jalur', array(), '', array("id" => "jalur", "class" => 'chosen-select form-control drop_select')); ?>
        </div>
    </div>
    <div class="col-sm-1">
        <span style="display: none" class="fa fa-2x fa-spinner fa-spin"></span>
    </div>
</div>

<div class="col-lg-12 top-modal-space" style="margin-left: 1px; margin-top: 0px;">
    <div id="group-nama" class="form-group">
        <label class="form-label col-sm-8" >Fakultas</label>
        <div class="col-sm-12">
            <?php echo form_dropdown('fakultas', $fakultas,$this->session->user['fakultas'], array("id" => "fakultas", "data-placeholderr" => "Pilih Fakultas", 'class' => 'form-control chosen-select drop_select')); ?>
        </div>
    </div>
</div>

<div class="col-lg-12 top-modal-space" style="margin-left: 1px; margin-top: 0px;">
    <div id="group-nama" class="form-group">
        <label class="form-label col-sm-8" >Program Studi </label>
        <div class="col-sm-12">
            <?php echo form_dropdown('prodi', array(), '', array("id" => "prodi", "data-placeholderr" => "Pilih Fakultas Dulu", "class" => 'chosen-select form-control drop_select')); ?>
        </div>
    </div>
    <div class="col-sm-1">
        <span style="display: none" class="fa fa-2x fa-spinner fa-spin"></span>
    </div>
</div>

<?php echo form_close(); ?>
<?php
    if(isset($this->session->user['prodi'])) $prodi=$this->session->user['prodi']; else $prodi=false;
    if(isset($this->session->user['jalur'])) $jalur=$this->session->user['jalur']; else $jalur=false;
?>
<script>
    $(document).ready(function () {
        var prodi = <?php echo "'".$prodi."'" ?>;
        var jalur = <?php echo "'".$jalur."'" ?>;
        var fakultas = $("#fakultas").val();
        var tahun = $("#tahun").val();
        $("#prodi").append($('<option>').text("Semua").attr('value',"all"));
        $("#jalur").append($('<option>').text("Semua").attr('value',"all"));
        if(fakultas != 'all'){            
            $("#prodi").empty();
            $.ajax({
                url:'<?php echo base_url('filter/listProdi');?>/'+fakultas,
                dataType:'JSON',
                beforeSend:function(){
                    $('.fa-spin').show();
                },
                success:function(data){
                    $('.fa-spin').hide();
                    $("#prodi").append($('<option>').text("Semua").attr('value',"all"));
                    $.each(data, function (i, obj) {
                        $("#prodi").append($('<option>').text(obj.prodiNamaResmi).attr('value',obj.prodiKode));
                    });
                    $("#prodi").val(prodi);
                    $("#prodi").trigger("chosen:updated");
                }
            });
        }
        if(tahun != 'all'){
            $("#jalur").empty();
            $.ajax({
                url:'<?php echo base_url('filter/listJalur');?>/'+tahun,
                dataType:'JSON',
                beforeSend:function(){
                    $('.fa-spin').show();
                },
                success:function(data){
                    $('.fa-spin').hide();
                    $("#jalur").append($('<option>').text("Semua").attr('value',"all"));
                    $.each(data, function (i, obj) {
                        $("#jalur").append($('<option>').text(obj.daftarJalur).attr('value',obj.daftarId));
                    });
                    $("#jalur").val(jalur);
                    $("#jalur").trigger("chosen:updated");
                }
            });
        }
    });
    
    $(function(){
        $("#fakultas").change();
    });
    $("#fakultas").change(function () {
        var val = $(this).val();
        if(val){
            $("#prodi").empty();
            $.ajax({
                url:'<?php echo base_url('filter/listProdi');?>/'+val,
                dataType:'JSON',
                beforeSend:function(){
                    $('.fa-spin').show();
                },
                success:function(data){
                    $('.fa-spin').hide();
                    $("#prodi").append($('<option>').text("Semua").attr('value',"all"));
                    $.each(data, function (i, obj) {
                        $("#prodi").append($('<option>').text(obj.prodiNamaResmi).attr('value',obj.prodiKode));
                    });
                    $("#prodi").trigger("chosen:updated");
                }
            });
        }
    });
    $(function(){
        $("#tahun").change();
    });
    $("#tahun").change(function () {
        var val = $(this).val();
        if(val){
            $("#jalur").empty();
            $.ajax({
                url:'<?php echo base_url('filter/listJalur');?>/'+val,
                dataType:'JSON',
                beforeSend:function(){
                    $('.fa-spin').show();
                },
                success:function(data){
                    $('.fa-spin').hide();
                    $("#jalur").append($('<option>').text("Semua").attr('value',"all"));
                    $.each(data, function (i, obj) {
                        $("#jalur").append($('<option>').text(obj.daftarJalur).attr('value',obj.daftarId));
                    });

                    $("#jalur").trigger("chosen:updated");
                }
            });
        }
    });
</script>
