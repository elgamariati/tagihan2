<!-- BEGIN PAGE CONTAINER-->

<style>
    .error_msg {
        color: red
    }
</style>
<div class="page-content">
    <!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->
    <div class="content">
        <div class="page-title col-lg-8 col-md-8 col-sm-8 col-xs-8">
            <i class="fa fa-address-book-o"></i>
            <h3><span class="semi-bold">TAGIHAN SPP/UKT</span><span style="font-size : 11pt ;"> Manajemen data tagihan untuk SPP/UKT.</span></span></h3>
        </div>
        <div class="row">
            <?php echo form_open(current_url(), array('class' => 'myform form-horizontal col-md-12 col-sm-12 col-xs-12')); ?>
                <div class="col-md-12">
                    <div class="grid simple">
                        <div class="grid-title no-border">
                            <h4>Tagihan  <span class="semi-bold">Tagihan</span></h4>
                            <div class="tools">
                                <a href="javascript:;" class="collapse"></a>
                            </div>
                        </div>
                        <div class="grid-body no-border">
                            <div class="row-fluid">
                                <?php if ($msg!=""){?>
                                <div class="alert alert-<?php echo ($msg_status?"success":"alert"); ?>">
                                    <button type="button" class="close" data-dismiss="alert"></button>
                                   
                                    <?php echo $msg; ?>
                                </div>
                                <?php } ?>
                                <div class="span3">
                                    <h4><i class="i  i-user3"></i> Data Penerima Tagihan</h4>
                                    <hr>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="nomor_pembayaran">Nomor Induk :</label>
                                        <div class="col-sm-6">
                                          <?php echo form_input('nomor_induk', set_value('nomor_induk', $tagihan["nomor_induk"]), array('class' => 'form-control nomor_induk','placeholder' => 'Isikan nomor induk')); ?>
                                   
                                        </div>
                                        
                                        <div class="error_msg col-sm-3">
                                            <?php echo (form_error('nomor_induk') != "" ? "Wajib diisi":"");?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="nomor_pembayaran">Nomor Pembayaran :</label>
                                        <div class="col-sm-6">
                                          <?php echo form_input('nomor_pembayaran', set_value('nomor_pembayaran', $tagihan["nomor_pembayaran"]), array('class' => 'form-control nomor_pembayaran','placeholder' => 'Isikan nomer pembayaran')); ?>
                                   
                                        </div>
                                        
                                        <div class="error_msg col-sm-3">
                                            <?php echo (form_error('nomor_pembayaran') != "" ? "Wajib diisi":"");?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="nama">Nama :</label>
                                        <div class="col-sm-6">
                                          <?php echo form_input('nama', set_value('nama', $tagihan["nama"]), array('class' => 'form-control nama','placeholder' => 'Isikan nama penerima tagihan')); ?>
                                   
                                        </div>
                                        
                                        <div class="error_msg col-sm-3">
                                            <?php echo (form_error('nama') != "" ? "Wajib diisi":"");?>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="nama">Fakultas :</label>
                                        <div class="col-sm-6">
                                          <?php 
                                                $ref_fakultas = array(""=>"Pilih fakultas")+$ref_fakultas;
                                                echo form_dropdown('kode_fakultas',$ref_fakultas,set_value('kode_fakultas', $tagihan["kode_fakultas"]),array('class' => 'chosen-select form-select drop_select fakultas','style'=>'width:100%'));
                                          ?>
                                        </div>
                                        <div class="error_msg  col-sm-3">
                                            <?php echo (form_error('kode_fakultas') != "" ? "Wajib diisi":"");?>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="nama">Program Studi :</label>
                                        <div class="col-sm-6">
                                            <?php 
                                                $ref_prodi = array(""=>"Pilih program studi");
                                                echo form_dropdown('kode_prodi',$ref_prodi,$tagihan['kode_prodi'],array('class' => 'chosen-select form-select drop_select prodi','style'=>'width:100%'));?>
                                        </div>
                                        <div class="error_msg  col-sm-3">
                                            <?php echo (form_error('kode_prodi') != "" ? "Wajib diisi":"");?>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="nama">Angkatan :</label>
                                        <div class="col-sm-6">
                                            <?php echo form_input('angkatan', set_value('angkatan', $tagihan["angkatan"]), array('class' => 'form-control angkatan','placeholder' => 'Isikan angkatan mahasiswa')); ?>
                                        </div>
                                        
                                        <div class="error_msg  col-sm-3"">
                                            <?php echo (form_error('angkatan') != "" ? "Wajib diisi":"");?>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="nama">Jenjang :</label>
                                        <div class="col-sm-6">
                                            <?php echo form_input('strata', set_value('strata', $tagihan["strata"]), array('class' => 'form-control strata','placeholder' => 'Isikan jenjang pendidikan mahasiswa')); ?>
                                        </div>
                                        
                                        <div class="error_msg  col-sm-3">
                                            <?php echo (form_error('strata') != "" ? "Wajib diisi":"");?>
                                        </div>
                                    </div>
                                   
                                    
                                  
                                    <h4><i class="i  i-user3"></i> Data Tagihan</h4><hr>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="nama">Nilai Tagihan :</label>
                                        <div class="col-sm-6">
                                            <?php echo form_input('total_nilai_tagihan', str_replace(".","",set_value('total_nilai_tagihan', $tagihan["total_nilai_tagihan"])), array('class' => 'form-control total_nilai_tagihan','placeholder' => 'Isikan rupiah tagihan')); ?>
                                        </div>
                                        
                                        <div class="error_msg  col-sm-3">
                                            <?php echo (form_error('total_nilai_tagihan') != "" ? "Wajib diisi":"");?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="nama">Status Tagihan :</label>
                                        <div class="col-sm-6">
                                        <?php
                                            $ref_aktif = array("1"=>"Aktif","0"=>"Tidak Aktif");
                                                echo form_dropdown('is_tagihan_aktif',$ref_aktif,$tagihan['is_tagihan_aktif'],array('class' => 'chosen-select form-select drop_select','style'=>'width:100%'));
                                        ?>   
                                        </div>
                                        <div class="error_msg col-md-3">
                                            <?php echo (form_error('is_tagihan_aktif') != "" ? "Wajib diisi":"");?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="nama">Prioritas Tagihan :</label>
                                        <div class="col-sm-6">
                                            <?php echo form_input('urutan_antrian', set_value('urutan_antrian', $tagihan["urutan_antrian"]), array('class' => 'form-control urutan_antrian','placeholder' => 'Isikan prioritas tagihan')); ?>
                                        </div>
                                        
                                        <div class="error_msg  col-sm-3">
                                            <?php echo (form_error('urutan_antrian') != "" ? "Wajib diisi":"");?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="nama">Masa Berlaku :</label>
                                        <div class="col-sm-3">
                                            <div class='input-group date' >
                                                <?php echo form_input('waktu_berlaku', set_value('waktu_berlaku', $tagihan["waktu_berlaku"]), array('class' => 'form-control waktu_berlaku','id'=>'waktu_berlaku','placeholder' => 'Isikan rupiah tagihan')); ?>
                                                <span class="input-group-addon">
                                                    <span class="glyphicon glyphicon-calendar"></span>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class='input-group' >
                                                <?php echo form_input('waktu_berakhir', set_value('waktu_berakhir', $tagihan["waktu_berakhir"]), array('class' => 'form-control waktu_berakhir','id'=>'waktu_berakhir','placeholder' => 'Isikan rupiah tagihan')); ?>
                                                <span class="input-group-addon">
                                                    <span class="glyphicon glyphicon-calendar"></span>
                                                </span>
                                            </div>
                                        </div>
                                       <div class="error_msg  col-sm-3">
                                            <?php echo ((form_error('waktu_berakhir') != "" | form_error('waktu_berlaku') != "") ? "Wajib diisi":"");?>
                                        </div>
                                    </div>
                                    
                                    
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="nama">Jenis Tagihan :</label>
                                        <div class="col-sm-6">
                                        <?php
                                            $ref_jenis = array("PEMBAYARAN"=>"PEMBAYARAN","VOUCHER"=>"VOUCHER");
                                                echo form_dropdown('pembayaran_atau_voucher',$ref_jenis,$tagihan['pembayaran_atau_voucher'],array('class' => 'chosen-select form-select drop_select pembayaran_atau_voucher','style'=>'width:100%'));?>
                                           
                                        </div>
                                        <div class="error_msg  col-sm-3">
                                            <?php echo (form_error('pembayaran_atau_voucher') != "" ? "Wajib diisi":"");?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 col-md-offset-5">
                    <center>
                        <?php  echo form_submit('Submit', "S U B M I T",array('class'=>'btn btn-primary  col-xs-12 col-md-2 col-lg-2','style'=>'')); ?>
                    </center>
                </div>
                </form>
        </div>
    </div>
</div>
<div id="myExist" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" ><h5 id="modal-title">Nomor Tagihan Ditolak</h5></div>
            <div class="modal-body">
                Nomor tagihan yang anda inputkan sudah tersimpan di periode ini. Harap masukkan dengan nomor tagihan yang lain.
            </div>
            <div class="modal-footer">
               
                <button id="btn-batal-simpan" data-dismiss="modal" class="btn modalbtn">Ok</button>
            </div>
        </div>
    </div>
</div>

 <link href="<?php echo base_url();?>assets/css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
 <script type="text/javascript" src="<?php echo base_url();?>assets/js/bootstrap-datetimepicker.js" charset="UTF-8"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/bootstrap-datetimepicker.id.js" charset="UTF-8"></script>
 <script>
    $(document).ready(function () {
        $('#waktu_berlaku').datetimepicker({
            pickerPosition:'top-right',
            language:  'id',
            format: "yyyy-mm-dd hh:ii:ss"
        });
        $('#waktu_berakhir').datetimepicker({
            pickerPosition:'top-right',
            language:  'id',
            format: "yyyy-mm-dd hh:ii:ss"
        });
        $('.total_nilai_tagihan').maskMoney({allowNegative: true, thousands:'.', precision:0});
        <?php if ($mode_input=="add"){ ?>
        $('.nomor_induk').focusout(function(){
		//alert($(this).val());
            if ($(this).val()!=""){
		$('.spin-nomor-pembayaran').show();
		$.ajax({
			url: "<?php echo base_url();?>spp/cek_tagihan",
			type: 'POST',
			data: {'nomor_induk':$(this).val(),'kode_periode':'<?php echo $kode_periode;?>'},
			success:function(res){
                            data=jQuery.parseJSON(res);
                            $('input, select').prop("disabled",false)
                            if (data.status){
                                $('.nomor_pembayaran').val(data.data.nomor_pembayaran);
                                $('.nama').val(data.data.mhsNama);
                                $('.fakultas').val(data.data.fakKode);
                                $( ".fakultas" ).trigger( "change", [data.data.prodiKode] );
                                $('.angkatan').val(data.data.mhsAngkatan);
                                $('.strata').val(data.data.prodiJjarKode);
                            } else {
                                $("#myExist").modal({
                                        backdrop: 'static',
                                        keyboard: true, 
                                        show: true
                                });
                                //$('input, select').prop("disabled",true);
                            }
                            //$('.prodi').val(data.data.prodiKode);
			}		
		});
            }
	});
        <?php } ?>
        $('.fakultas').change(function(event, a){
		//alert($(this).val());
		$('.prodi').html("<option>Loading...</option>");
		$.ajax({
			url: "<?php echo base_url();?>spp/get_prodi",
			type: 'POST',
			data: {'fakultas':$(this).val()},
			success:function(data){
				$('.prodi').html(data);
                                if (a!=null)
                                    $('.prodi option[value='+a+']').prop('selected',true);
                                else
                                    $('.prodi option[value=<?php echo set_value('kode_prodi', $tagihan['kode_prodi'])?>]').prop('selected',true);
			}		
		});
	});
        $('.fakultas').trigger("change");
        
     
    });
</script>
</div>