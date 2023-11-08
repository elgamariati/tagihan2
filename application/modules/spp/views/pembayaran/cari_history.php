
<!-- BEGIN PAGE CONTAINER-->
<div class="page-content">
    <!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->
    <div class="content">
        <div class="page-title col-lg-8 col-md-8 col-sm-8 col-xs-8">
            <i class="fa fa-address-book-o"></i>
            <h3><span class="semi-bold">Pembayaran Uang Kuliah</span><span style="font-size : 11pt ;"> >> Pengelolaan Data per Mahasiswa</span></h3>
        </div>

        <div class="page-title col-lg-8 col-md-8 hidden-md hidden-xs hidden-sm">
            <i class="fa fa-info-circlee"></i>
        </div>

        <div class="row-fluid">
            <div class="col-sm-12"> 
                <div class="grid simple ">
                    <div class="grid-title">
                        <h4>Data Pembayaran<?php echo $label_jenjang; ?></h4>
                        <div class="tools">
                            <a href="javascript:;" class="collapse"></a>
                        </div>
                    </div>
                    <div class="grid-body">
                        <div class="col-sm-5 row">
                            <div class="cari_nim">
                                <?php echo form_open(current_url(), array('class' => 'myform form-horizontal col-md-12 col-sm-12 col-xs-12')); ?>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3 text-left" for="nomor_induk">NIM</label>
                                        <div class="col-sm-9">
                                            <?php echo form_input('nomor_induk', '', array('class' => 'form-control nomor_induk','placeholder' => 'Isikan nomor induk')); ?>
                                        </div>
                                    </div> 
                                    <div class="col-sm-3">
                                    </div>
                                    <div class="col-sm-9">
                                        <a href="#" class="btn btn-success proses">Cari Pembayaran <span class="fa fa-spinner fa-spin"></span></a>
                                    </div>
                                </form>
                            </div>
                            <div class="hasil_nim" style="display:none">
                                <div class="form-group">
                                    <label class="control-label col-sm-3 text-left" for="nomor_induk">NIM</label>
                                    <label class="control-label col-sm-9  text-left hasil_cari_nim">
                                    </label>
                                </div> 
                                <div class="form-group">
                                    <label class="control-label col-sm-3 text-left" for="nama">Nama</label>
                                    <label class="control-label col-sm-9  text-left hasil_cari_nama">
                                    </label>
                                </div> 
                                <div class="form-group">
                                    <label class="control-label col-sm-3 text-left" for="angkatan">Angkatan</label>
                                    <label class="control-label col-sm-9  text-left hasil_cari_angkatan">
                                    </label>
                                </div> 
                                <div class="form-group">
                                    <label class="control-label col-sm-3 text-left" for="prodi">Prodi</label>
                                    <label class="control-label col-sm-9  text-left hasil_cari_prodi">
                                    </label>
                                </div> 
                                <div class="form-group">
                                    <label class="control-label col-sm-3 text-left" for="fakultas">Fakultas</label>
                                    <label class="control-label col-sm-9  text-left hasil_cari_fakultas">
                                    </label>
                                </div> 
                                <div class="col-sm-3">
                                </div>
                                <div class="col-sm-9">
                                    <a href="#" class="btn btn-success reset">Cari Lagi</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-7">
                            <table class="table table-hover table-condensed" id="table-datatable" style="font-size:8px" width='100%'>
                                <thead>
                                    <tr>
                                        <th width="5%">Sem</th>
                                        <th width="15%">Jumlah</th>
                                        <th width="20%">Waktu</th>
                                        <th width="15%">Kanal</th>
                                        <th width="15%">Bank</th>
                                        <th width="25%">Aksi</th>
                                    </tr>
                                </thead>
                            </table>
                            <div class="col-sm-1" style="background-color:#f8cdcd">&nbsp;</div>                                                        
                            <div class="col-sm-11">Pembayaran "Cuti Kuliah"</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</section>

<div id="myAdd" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="modal-title">Ubah Pembayaran</h5>
            </div>
            <div class="modal-body">
                <div class="simpan-msg"></div>
                <div class="form-group">
                    <div class="col-sm-12">
                        Id Record Pembayaran
                        <div>
                            <?php echo form_input('id_record_pembayaran','', array('class' => 'form-control id_record_pembayaran','id'=>'id_record_pembayaran')); ?>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        Periode
                        <div>
                            <?php echo form_input('key_val_5','', array('class' => 'form-control key_val_5','id'=>'key_val_5')); ?>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        Nilai Pembayaran
                        <div>
                            <?php echo form_input('total_nilai_pembayaran','', array('class' => 'form-control total_nilai_pembayaran','id'=>'total_nilai_pembayaran')); ?>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        Waktu Transaksi
                        <div>
                            <?php echo form_input('waktu_transaksi','', array('class' => 'form-control waktu_transaksi','id'=>'waktu_transaksi')); ?>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        Kanal
                        <div>
                            <?php echo form_input('kanal_bayar_bank','', array('class' => 'form-control kanal_bayar_bank','id'=>'kanal_bayar_bank')); ?>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        Bank
                        <div>
                            <?php echo form_input('kode_bank','', array('class' => 'form-control kode_bank','id'=>'kode_bank')); ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button id="btn-simpan" class="btn btn-primary modalbtn">
                    <span class="fa fa-spinner fa-spin"></span> <i class="fa fa-save" aria-hidden="true"></i> Simpan
                </button>
                <button id="btn-simpan-batal" data-dismiss="modal" class="btn modalbtn">Tutup</button>
            </div>
        </div>
    </div>
</div>

<div id="myHapus" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">Hapus Pembayaran</div>
            <div class="modal-body">
                <div class="form-group col-lg-12 col-md-12">
                    Apakah anda yakin ingin menghapus pembayaran?
                </div>
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

<div id="myError" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" ><h5 id="modal-title" class="box_error_header"></h5></div>
            <div class="modal-body box_error_konten"></div>
            <div class="modal-footer">
                <button id="btn-batal-simpan" data-dismiss="modal" class="btn modalbtn">Tutup</button>
            </div>
        </div>
    </div>
</div>
<link href="<?php echo base_url();?>assets/css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
<script type="text/javascript" src="<?php echo base_url();?>assets/js/bootstrap-datetimepicker.js" charset="UTF-8"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/bootstrap-datetimepicker.id.js" charset="UTF-8"></script>
<script>
    var oTable;
    var id_delete = "";
    var is_allow_add=false;
    var nomor_induk="";
    var id_record_tagihan="";

    function hapus() {
        var id = id_delete;
        $.ajax({
            url: "<?php echo base_url('spp/pembayaran/delete'); ?>",
            data: {
                'id': id
            },
            type: "GET",
            dataType: 'JSON',
            beforeSend: function() {
                $(".fa-spinner").show();
                $("#btn-hapus").attr("disabled", true);
                $("#btn-batal").attr("disabled", true);
            },
            success: function(data) {
                $(".fa-spinner").hide();
                $("#btn-hapus").removeAttr("disabled");
                $("#btn-batal").removeAttr("disabled");
                if (data.status) {
                    $("#btn-hapus").hide();
                    $("#btn-batal").html("Tutup");
                }
                oTable.api().ajax.reload();
                $("#myHapus .modal-body").html(data.msg);
            },
        });
    }

    function detail(id) {
        $('#myDetail').modal("show");
        $.ajax({
            url: "<?php echo base_url('spp/pembayaran/detail/');?>" + id,

            type: "GET",
            success: function(data) {

                $(".detail").html(data);

            }
        });
    }

    function hapus_dialog(id) {
        //alert("asd");
        $('#myHapus').modal("show");
        id_delete = id;

    }

    function add_modal() {
        $("#btn-simpan").show();
        $(".simpan-msg").html("");
        id_record_tagihan="";
        if (is_allow_add)
        {
            $('#myAdd').modal("show"); 
            $("#periode").attr("disabled", false);
        }
        else {
            $(".box_error_header").html("Operasi Gagal");
            $(".box_error_konten").html("Silakan cari NIM yang tagihannya ingin ditambahkan");
            $('#myError').modal("show"); 
        }

    }
    
    function edit_modal(id) {
        $("#btn-simpan").show();
        $(".simpan-msg").html("");
        if (is_allow_add){
            $('#myAdd').modal("show"); 
            $.ajax({
                url: "<?php echo base_url('spp/pembayaran/getPembayaran'); ?>",
                data: {
                    'id_record_pembayaran': id
                },
                type: "POST",
                dataType: 'JSON',
                beforeSend: function() {
                },
                success: function(data) {
                    id_record_pembayaran=id;
                    $('#id_record_pembayaran').val(data.id_record_pembayaran).prop("readonly",true);
                    $('#key_val_5').val(data.key_val_5).prop("readonly",true);
                    $('#total_nilai_pembayaran').val(data.total_nilai_pembayaran);
                    $('#waktu_transaksi').val(data.waktu_transaksi).prop("readonly",true);
                    $('#kanal_bayar_bank').val(data.kanal_bayar_bank).prop("readonly",true);
                    $('#kode_bank').val(data.kode_bank).prop("readonly",true);
                },
            });
        }
        else {
            $(".box_error_header").html("Operasi Gagal");
            $(".box_error_konten").html("Menu edit pembayaran tidak berfungsi sebelum anda menngisi nomor induk.");
            $('#myError').modal("show"); 
        }

    }

    $(document).ready(function() {
        $('.total_nilai_pembayaran').maskMoney({allowNegative: false, thousands:'.', precision:0});
        $(".hasil_nim").hide();
        var formTitle = $("#modal-form #modal-title").html();
        var formBody = $("#modal-form .modal-body").html();
        var formFooter = $("#modal-form .modal-footer").html();
        $("#modal-form").modal({
            backdrop: "static",
            show: false
        });
        $("#modal-form").on("show.bs.modal", function() {
            $("#modal-form #modal-title").html(formTitle);
            $("#modal-form .modal-body").html(formBody);
            $("#modal-form .modal-footer").html(formFooter);
            $(".fa-spinner").hide();
            $("#pesan-error").hide();
        });

        oTable = $('#table-datatable').dataTable({
            "processing": true, //Feature control the processing indicator.
            "language": { processing: '<div class="alert alert-danger">Memroses data <span class="fa fa-spinner fa-spin"></span></div>'},
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "order": [
                [4, "desc"]
            ],
            columns: [
                {data: '7'},
                {data: '3'},
                {data: '4'},
                {data: '5'},
                {data: '6'},
                {data: '8'}
            ],
            "bPaginate": false,
            "bInfo": false,
            "rowCallback": function( row, data, index ) {
                if ( data[9] == 2 )
                    $('td', row).css('background-color', '#f8cdcd');
            },
            "ajax": {
                "url": "<?php echo site_url('spp/pembayaran/ajax_mhs_bayar')?>",
                "type": "POST",
                "data": function (d) {
                    return $.extend({}, d, {
                        "nomor_induk": $('.nomor_induk').val()
                    });
                }
            },

            //Set column definition initialisation properties.
            "columnDefs": [{
                "targets": [0], //first column / numbering column
                "orderable": false, //set not orderable
            }, ],
        });

        $(".dataTables_filter, .dataTables_length").hide();
     
        $(document).on('click','.proses',function(form){
            $.ajax({
            url: "<?php echo base_url('spp/pembayaran/get_mahasiswa_bayar'); ?>",
            data: {
                'nomor_induk': $(".nomor_induk").val()
            },
            type: "POST",
            dataType: 'JSON',
            beforeSend: function() {
                $(".fa-spinner").show();
            },
            success: function(data) {
                $(".fa-spinner").hide();
                if (data.status) 
                {
                    nomor_induk=$(".nomor_induk").val();
                    is_allow_add=true;
                    $(".cari_nim").hide();
                    $(".hasil_nim").show();
                    $(".hasil_cari_nim").html($(".nomor_induk").val());
                    $(".hasil_cari_nama").html(data.data.mhsNama);
                    $(".hasil_cari_angkatan").html(data.data.mhsAngkatan);
                    $(".hasil_cari_prodi").html(data.data.prodiNamaResmi);
                    $(".hasil_cari_fakultas").html(data.data.fakNamaSingkat);
                    oTable.api().ajax.reload();
                }
                else
                {
                    $(".box_error_header").html("Pencarian Gagal");
                    $(".box_error_konten").html(data.msg);
                    $('#myError').modal("show"); 
                }
            },
        });
	});

    $(document).on('click','.reset',function(form){
        is_allow_add=false;
        $(".nomor_induk").val("");
        oTable.api().ajax.reload();
        $(".cari_nim").show();
        $(".hasil_nim").hide();
        
    });

    $(document).on('click','#btn-simpan',function(form){
        $.ajax({
            url: "<?php echo base_url('spp/pembayaran/simpan_pembayaran'); ?>",
            data: {
                'id_record_pembayaran' : id_record_pembayaran,
                'total_nilai_pembayaran':$('#total_nilai_pembayaran').val()
            },
            type: "POST",
            dataType: 'JSON',
            beforeSend: function() {
                $(".fa-spinner").show();
                $("#btn-simpan").attr("disabled", true);
                $("#btn-simpan-batal").attr("disabled", true);
            },
            success: function(data) {
                $(".fa-spinner").hide();
                $("#btn-simpan").removeAttr("disabled");
                $("#btn-simpan-batal").removeAttr("disabled");
                $(".simpan-msg").html(data.msg);
                if (data.status) {
                    $("#btn-simpan").hide();
                    oTable.api().ajax.reload();
                }            
            },
        });
    });
       
});
</script>