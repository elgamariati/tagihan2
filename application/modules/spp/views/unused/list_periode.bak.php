
<!-- BEGIN PAGE CONTAINER-->
<div class="page-content">
    <!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->
    <div class="content">
        <div class="page-title col-lg-8 col-md-8 col-sm-8 col-xs-8">
            <i class="fa fa-address-book-o"></i>
            <h3><span class="semi-bold">Tagihan Uang Kuliah</span><span style="font-size : 11pt ;"> >> Penyalinan dan Pengelolaan Data Semester</span></h3>

        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
            <div class="dropdown" style="float : right ; margin-right : -14px">
                <button class="btn btn-primary pull-right" type="button" onclick="dialog_copy();">Salin Tagihan</button>
            </div>
        </div>
        <div class="page-title col-lg-8 col-md-8 hidden-md hidden-xs hidden-sm">
            <i class="fa fa-info-circlee"></i>

        </div>
        <!--       <div class="col-lg-4 col-md-4">
                  <a href="#" data-toggle="modal" data-backdrop="static" data-target="#modal-form" class="btn btn-primary btn-cons" style="float : right ; margin-right : -14px"><i class="fa fa-plus"></i></i><span>Tambah Data</span></a>
               </div>-->

        <div class="row-fluid">
            <div class="span12">
                <div class="grid simple ">
                    <!--
                    <a href="#" onclick="dialog_tambah();"class="cardadd_box" >
                        <div class="grid-body col-md-4" id="btn_tambah_periode">
                            <div class="card">

                                <div class="card-body">
                                    
                                    <center>
                                        <i class="fa fa-plus  fa-5x"></i><br>
                                        Tambah Periode Baru
                                    </center>
                                  
                                </div>
                            </div>

                        </div>
                    </a>-->
                    <?php foreach ($listPeriode as $row) { ?>
                        <div class="grid-body col-md-4 card_<?php echo $row->kode_periode;?>">
                            <div class="card">
                                <!--
                                <?php if ($row->jumlah==0 || $row->jumlah==null) { ?>
                                    <div class="text-right"><a href="#"  onclick="hapus(<?php echo $row->kode_periode;?>);"><span class="fa fa-spinner fa-spin"></span><i class="fa fa-trash"></i> Hapus</a></div>
                                <?php } else { ?>
                                    <div class="text-right">&nbsp;</div>
                                <?php } ?>
                                -->
                                <div class="card-body">
                                    
                                    <h4 class="card-title"><center><?php echo $row->nama_periode; ?></center></h4>
                                    <ul class="list-group">
                                        <li class="list-group-item">
                                            <div class="row">
                                                <div class="col-md-4"><span class="small bold">Total data</span></div>
                                                <div class="col-md-8 text-right"><?php echo ($row->jumlah != null ? number_format($row->jumlah, 0, ",", ".") : 0); ?></div>
                                            </div>
                                        </li>
                                        <li class="list-group-item">
                                            <div class="row">
                                                <div class="col-md-4"><span class="small bold">Total tagihan</span></div>
                                                <div class="col-md-8 text-right"><?php echo ($row->total != null ? rupiah($row->total) : 0); ?></div>
                                            </div>
                                        </li>
                                        <li class="list-group-item">
                                            <div class="row">
                                                <div class="col-md-4"><span class="small bold">Total dibayar</span></div>
                                                <div class="col-md-8 text-right"><?php echo ($row->total != null ? rupiah($row->total_bayar) : 0); ?></div>
                                            </div>
                                        </li>

                                    </ul>

                                    <a href="<?php echo base_url() . "spp/tagihan/" . $row->kode_periode; ?>" class="btn btn-primary">Kelola Data</a>
                                </div>
                            </div>

                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="modal-tambah" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">Tambah Periode</div>
            <div class="modal-body">
                <div class="message_box"></div>
                <?php echo form_open(current_url(), array('id'=>"form-simpan",'class' => 'form-horizontal col-md-12 col-sm-12 col-xs-12'));?>
                <div class="form-group col-lg-12 col-md-12 ">
                    <label class="form-label">Periode Tahun</label>
                    <div class="controls">
                        <?php echo form_input('tahun', '', array('class' => 'form-control', 'placeholder' => 'Tahun periode')); ?>   
                    </div>
                    <br>
                    <label class="form-label">Periode Semester</label>
                    
                    <div class="controls">
                        <?php echo form_dropdown('semester', array("1" => "Ganjil", "2" => "Genap", "3" => "Antara"), '', array('class' => 'form-control', 'placeholder' => 'Tahun periode')); ?>   
                    </div>
                </div>
                <?php echo form_close();?>
            </div>
            <div class="modal-footer" style="text-align: center;">
                <button id="btn-tambah" class="btn btn-primary" onclick="do_tambah();">
                    <span class="fa fa-spinner fa-spin"></span> <i class="fa fa-save" aria-hidden="true"></i> Simpan
                </button>
                <button id="btn-batal" data-dismiss="modal" class="btn">Tutup</button>
            </div>
        </div>
    </div>
</div>
<div id="modal-hapus" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">Hapus Peminat</div>
            <div class="modal-body">
                Anda yakin menghapus Peminat dengan nomor peserta "<span id="id-delete"></span>"?
            </div>
            <div class="modal-footer" style="text-align: center;">
                <button id="btn-hapus" class="btn btn-primary" onclick="hapus();">
                    <span class="fa fa-spinner fa-spin"></span> <i class="fa fa-trash" aria-hidden="true"></i> Hapus
                </button>
                <button id="btn-batal" data-dismiss="modal" class="btn">Tutup</button>
            </div>
        </div>
    </div>
</div>

<div id="myCopy" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header" ><h5 id="modal-title">Salin Tagihan</h5></div>
            <div class="modal-body">
                 <div class="form-group col-lg-12 col-md-12 ">
                    <div class="row">
                        <div class="col-sm-6">
                            <label class="form-label">Periode Sumber</label>
                            <div class="controls">
                                <?php echo form_dropdown('periode_sumber', $listPeriode_min,'', array('id'=>'periode_sumber', 'class' => 'form-control', 'placeholder' => 'Tahun periode')); ?>   
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label">Periode Target</label>
                            <div class="controls">
                                <?php echo form_dropdown('periode_target', $listPeriode_min, '', array('id'=>'periode_target','class' => 'form-control', 'placeholder' => 'Tahun periode')); ?>   
                            </div>                        
                        </div>
                    </div>                    
                    <label class="form-label"></label>
                    <div class="copy-konfimasi"></div>
                </div>
               
            </div>
            <div class="modal-footer">
                <button id="btn-proses-copy" class="btn btn-primary modalbtn">
                    <span class="fa fa-spinner fa-spin"></span><i class="fa fa-copy" aria-hidden="true"></i> Proses
                </button>               
                <button id="btn-upload-batal" data-dismiss="modal" class="btn modalbtn">Tutup</button>
            </div>

        </div>
    </div>
</div>
<div id="myDownloadTolak" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" ><h5 id="modal-title">Download/Upload Tempate Ditolak</h5></div>
            <div class="modal-body">
                Sebelum anda mendownload atau meupload template, harap setting JALUR MASUK dimenu Filter. Satu buah file template hanya digunakan untuk satu list data jalur masuk.
            </div>
            <div class="modal-footer">

                <button id="btn-batal-simpan" data-dismiss="modal" class="btn modalbtn">Ok</button>
            </div>
        </div>
    </div>
</div>

</section>


<link href="<?php echo base_url();?>assets/css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
<script type="text/javascript" src="<?php echo base_url();?>assets/js/bootstrap-datetimepicker.js" charset="UTF-8"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/bootstrap-datetimepicker.id.js" charset="UTF-8"></script>

<script>
    var oTable;
    var id_delete = "";
    function setModalHapus(dom, row1) {
        var id = dom.data('id');
        id_delete = id;
        console.log(row1);
        $(".fa-spinner").hide();
        $("#btn-hapus").show();
        $("#btn-batal").html("Batal");
        $("#modal-hapus .modal-body").html("Anda yakin menghapus Pendaftar dengan Nama Peserta \"<span id='id-delete'></span>\"?");
        $("#id-delete").html(row1);
    }
    function hapus(periode) {
        $.ajax({
            url: "<?php echo base_url('spp/periode_delete'); ?>",
            data: {'kode_periode':periode},
            type: "POST",
            dataType: 'JSON',
            beforeSend: function () {
                $(".card_"+periode+" .fa-spinner").show();
                $(".card_"+periode+" .fa-trash").hide();
            },
            success: function (data) {
                $(".card_"+periode+" .fa-spinner").hide();
                $(".card_"+periode+" .fa-trash").show();
                if (data.status) {
                    $(".card_"+periode).hide(1000);
                }
                
            },
        });
        
        
    }
    function dialog_tambah(){
        $(".message_box").html("");
        $("#modal-tambah").modal("show");
    }
    function dialog_copy(){
        $(".message_box").html("");
        $('#btn-proses-copy').show();
        $(".copy-konfimasi").html("");
        $("#myCopy").modal("show");
    }
    function do_tambah() {
        var data = $("#form-simpan").serialize();
        $.ajax({
            url: "<?php echo base_url('spp/periode_add'); ?>",
            data: data,
            type: "POST",
            dataType: 'JSON',
            beforeSend: function () {
                $(".fa-spinner").show();
                $("#btn-tambah").attr("disabled", true);
                $("#btn-batal").attr("disabled", true);
            },
            success: function (data) {
                $(".fa-spinner").hide();
                $("#btn-tambah").removeAttr("disabled");
                $("#btn-batal").removeAttr("disabled");
                if (data.status) {
                    $("#btn-hapus").hide();
                    $("#btn-batal").html("Tutup");
                    $(".message_box").html("<div class='alert alert-success'>" + data.message + "</div>");
                    var box='<div class="grid-body col-md-4 card_'+data.kode_periode+'">'+
                            '<div class="card">'+
                                '<div class="text-right"><a href="#"  onclick="hapus('+data.kode_periode+');"><span class="fa fa-spinner fa-spin"></span><i class="fa fa-trash"></i> Hapus</a></div>'+
                                
                                '<div class="card-body">'+
                                    
                                    '<h4 class="card-title"><center>'+data.nama_periode+'</center></h4>'+
                                    '<ul class="list-group">'+
                                        '<li class="list-group-item">'+
                                            '<div class="row">'+
                                                '<div class="col-md-4"><span class="small bold">Total data</span></div>'+
                                                '<div class="col-md-8 text-right">0</div>'+
                                            '</div>'+
                                        '</li>'+
                                        '<li class="list-group-item">'+
                                            '<div class="row">'+
                                                '<div class="col-md-4"><span class="small bold">Total tagihan</span></div>'+
                                                '<div class="col-md-8 text-right">0</div>'+
                                            '</div>'+
                                        '</li>'+
                                        '<li class="list-group-item">'+
                                            '<div class="row">'+
                                                '<div class="col-md-4"><span class="small bold">Total pembayaran</span></div>'+
                                                '<div class="col-md-8 text-right">0</div>'+
                                            '</div>'+
                                        '</li>'+

                                    '</ul>'+

                                    '<a href="<?php echo base_url() . "spp/tagihan/"; ?>'+data.kode_periode+'" class="btn btn-primary">Lihat Data</a>'+
                                '</div>'+
                            '</div>'+

                        '</div>';
                        $( box ).insertAfter( ".cardadd_box" );
                        $(".fa-spinner").hide();
                } else {
                    $(".message_box").html("<div class='alert alert-error'> " + data.message + "</div>");
                }
                
            },
        });
    }

    function eksport(obj) {
        var id = obj.data('id');
        $.ajax({
            url: "<?php echo base_url('pendaftar/eksport'); ?>",
            data: id,
            type: "GET",
            dataType: 'JSON',
            beforeSend: function () {
                $("#modal-form").modal('show');
                $("#modal-form #modal-title").html("Eksport data Pendaftar");
                $(".fa-spinner").show();
                $("#btn-simpan").attr("disabled", true);
            },
            success: function (data) {
                if (data.simpan) {
                    $.each(data.model, function (key, value) {
                        $("#" + key).val(value);
                    });
                    $("#kode").val(data.model.jllrKode);
                    $(".fa-spinner").hide();
                    $("#btn-simpan").removeAttr("disabled");
                } else {
                    $("#modal-form .form-body").html(data.pesan);
                }
            }
        });
    }
    
    function do_copy(sumber,target) 
    {
            $('#btn-lanjutkan-copy').html('Menyalin data...');
            $('#btn-lanjutkan-copy').prop('disabled', true);

            var form_data = new FormData();
            form_data.append("periode_sumber", sumber);
            form_data.append("periode_target", target);
            form_data.append("copy_waktu_berlaku", $('#copy_waktu_berlaku').val());
            form_data.append("copy_waktu_berakhir", $('#copy_waktu_berakhir').val());

            //$('#btn')
            $('.btn-lanjutkan-copy').append('&nbsp; <i class="fa fa-spin fa-spinner"></i>')

            $.ajax({
                url: "<?php echo site_url('spp/doCopyData') ?>",
                type: 'POST',
                processData: false,
                contentType: false,
                data: form_data,
                success: function (data) {
                    $(".copy-konfimasi").html(data);
                    $('#btn-proses-copy').show();
                }
            });
        };

    $(document).ready(function () 
    {

        var formTitle = $("#modal-form #modal-title").html();
        var formBody = $("#modal-form .modal-body").html();
        var formFooter = $("#modal-form .modal-footer").html();

        $("#modal-form").modal({backdrop: "static", show: false});
        $("#modal-form").on("show.bs.modal", function () {
            $("#modal-form #modal-title").html(formTitle);
            $("#modal-form .modal-body").html(formBody);
            $("#modal-form .modal-footer").html(formFooter);
            $(".fa-spinner").hide();
            $("#pesan-error").hide();
        });

        oTable = $('#table-datatable').dataTable({
            "processing": true, //Feature control the processing indicator.
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "order": [], //Initial no order.

            // Load data for the table's content from an Ajax source
            "ajax": {
                "url": "<?php echo site_url('spp/ajax_list') ?>",
                "type": "POST"
            },

            //Set column definition initialisation properties.
            "columnDefs": [
                {
                    "targets": [0], //first column / numbering column
                    "orderable": false, //set not orderable
                },
            ],
        });
        $("#field-cari").on('keyup', function (e) {
            var code = e.which;
            if (code == 13)
                e.preventDefault();
            if (code == 32 || code == 13 || code == 188 || code == 186) {
                oTable.fnFilter($("#field-cari").val());
            }
        });
        $("#btn-cari").click(function () {
            oTable.fnFilter($("#field-cari").val());
        });

        $("#btn-filter-proses").click(function () {
            var pil1 = "all";
            var pil2 = "all";
            var pil3 = "all";
            var bidikmisi = "all";
            if ($('.filter_prodi_1').val() != "")
                pil1 = $('.filter_prodi_1').val();
            if ($('.filter_prodi_2').val() != "")
                pil2 = $('.filter_prodi_2').val();
            if ($('.filter_prodi_3').val() != "")
                pil3 = $('.filter_prodi_3').val();
            if ($('.filter_bidikmisi').val() != "")
                bidikmisi = $('.filter_bidikmisi').val();
            else
                bidikmisi = "all";
            //oTable.ajax.url('<?php echo base_url('pendaftar'); ?>/filter/'+pil1+'/'+pil2+'/'+pil3+'/'+bidikmisi).load();
            //alert(pil1);
            var NTypSource = '<?php echo base_url('pendaftar'); ?>/filter/' + pil1 + '/' + pil2 + '/' + pil3 + '/' + bidikmisi;
            oTable.api().ajax.url(NTypSource).load();
        });
        $("#btn-filter-batal").click(function () {

            var NTypSource = '<?php echo base_url('pendaftar'); ?>';
            oTable.api().ajax.url(NTypSource).load();
        });
//        $("#field-cari").on('keyup', function(e) {
//            var code = e.which;
//            if(code==13)e.preventDefault();
//            if(code==32||code==13||code==188||code==186){
//                oTable.fnFilter($("#field-cari").val());
//            }
//        });
//        $("#btn-cari").click(function () {
//            oTable.fnFilter($("#field-cari").val());
//        });

        

        $("#btn-proses-copy").click(function () 
        {
            $('#btn-proses-copy').html('Menyiapkan data...');
            $('#btn-proses-copy').prop('disabled', true);

            var form_data = new FormData();
            form_data.append("periode_sumber", $('#periode_sumber').val());
            form_data.append("periode_target", $('#periode_target').val());
            $.ajax({
                url: "<?php echo site_url('spp/getCopyData') ?>",
                type: 'POST',
                processData: false,
                contentType: false,
                data: form_data,
                success: function (data) {
                    $(".copy-konfimasi").html(data);
                    $('#btn-proses-copy').prop('disabled', false);
                    $('#btn-proses-copy').hide();
                    $('#btn-proses-copy').html('<i class="fa fa-copy" aria-hidden="true"></i> Proses');
                    $('#copy_waktu_berlaku, #copy_waktu_berakhir').datetimepicker({
                        language: 'id',
                        format: "yyyy-mm-dd hh:ii:ss"
                    });

                }
            });
        });
    });
</script>
