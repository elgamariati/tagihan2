<style>
    ul li {
        list-style: none;
        margin-left: 0px;
        margin-bottom: 0px;
    }

    .dropdown-menu li {
        padding-left: 10px;
    }

    #status {
        font-size: 14px
    }

    #message {
        font-size: 12px
    }

    #box_sukses {
        border: 1px solid #099a8c;
        padding: 10px
    }

    #box_gagal {
        border: 1px solid #f14d4d;
        padding: 10px
    }

    th {
        font-size: 8px;
    }

    td {
        font-size: 10px;
    }
</style>
<!-- BEGIN PAGE CONTAINER-->
<div class="page-content">
    <!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->
    <div class="content">
        <div class="page-title col-lg-7 col-md-7 col-sm-7 col-xs-7">
            <i class="fa fa-file"></i>
            <h3><span class="semi-bold">Laporan Data Mahasiswa</span><span style="font-size : 11pt ;"> >> Laporan Data per Semester</span></h3>
        </div>
        <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
            <div class="dropdown" style="float : right ; margin-right : -14px">
                <a class="btn btn-primary" onclick="dialog_tambah()" href="#"> <span class="fa fa-download"></span> Unduh XLS</a>
            </div>
        </div>
        <div class="page-title col-lg-8 col-md-8 hidden-md hidden-xs hidden-sm">
            <i class="fa fa-info-circlee"></i>
        </div>

        <div class="row">
           
            <div class="col-md-4 pull-right">
                <div class="input-group">
                    <input type="text" id="field-cari" class="form-control" name="field-cari" placeholder="Pencarian">
                    <span class="input-group-btn">
                        <a class="btn btn-primary" id="btn-cari" href="#" value="Cari"><i class="fa fa-search">&nbspCari</i></a>
                    </span>
                </div>
                <br>
            </div>
        </div>

        <div class="row-fluid">
            <div class="span12">
                <div class="grid simple ">
                    <div class="grid-title">
                        <h4>Laporan Data Mahasiswa <?php echo $label_nama_periode . $label_jenjang; ?></h4>
                        <div class="tools">
                            <a href="javascript:;" class="collapse"></a>
                        </div>
                    </div>
                    <div class="grid-body ">
                        <table class="table table-hover table-condensed" id="table-datatable" style="font-size:8px" width='100%'>
                            <thead>
                                <tr>
                                    <th width="1%">NO</th>
                                    <th width="15%">NOPES</th>
                                    <th width="15%">NIM</th>
                                    <th width="17%">NAMA</th>
                                    <th width="6%">ANGKATAN</th>
                                    <th width="15%">KODE PRODI</th>
                                    <th width="6%">STRATA</th>
                                    <th width="15%">PRODI</th>
                                    <th width="15%">FAKULTAS</th>
                                   <!--  <th width="15%">JK</th>
                                    <th width="15%">TEMPAT LAHIR</th>
                                    <th width="15%">TANGGAL LAHIR</th>
                                    <th width="15%">NO HP</th>
                                    <th width="15%">NO HP ORTU</th>
                                    <th width="15%">NO HP WALI</th>
                                    <th width="15%">EMAIL</th> -->

                                    <!-- <th width="20%">AKSI</th> -->
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div id="modal-download" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">Download XLS</div>
            <div class="modal-body">
                <div class="message_box"></div>
                <div class="form-group col-lg-12 col-md-12 ">
                    <label class="form-label">Periode Aktif</label>
                    <div class="controls">
                        <p><b><?php echo $this->session->userdata('user')['periode_text'] ?></b></p>
                    </div>

                    <br>
                    <label class="form-label">Fakultas</label>
                    <div class="controls">
                        <?php
                        $ref_fakultas = array("" => "Semua Fakultas") + $fakultas;
                        echo form_dropdown('kode_fakultas', $ref_fakultas, set_value('kode_fakultas'), array('class' => 'chosen-select form-select drop_select fakultas', 'style' => 'width:100%', 'readonly'));
                        ?>
                    </div>
                    <br>
                    <label class="form-label">Program Studi</label>
                    <div class="controls">
                        <?php
                        $ref_prodi = array("" => "Semua Program Studi");
                        echo form_dropdown('kode_prodi', $ref_prodi, 0, array('class' => 'chosen-select form-select drop_select prodi', 'style' => 'width:100%', 'readonly')); ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="text-align: center;">
                <button id="btn-tambah" class="btn btn-primary" onclick="do_download();">
                    <span class="fa fa-spinner fa-spin"></span> <i class="fa fa-save" aria-hidden="true"></i> Download
                </button>
                <button id="btn-tambah-batal" data-dismiss="modal" class="btn">Tutup</button>
            </div>
        </div>
    </div>
</div>

</section>

<link href="<?php echo base_url(); ?>assets/css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootstrap-datetimepicker.js" charset="UTF-8"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootstrap-datetimepicker.id.js" charset="UTF-8"></script>
<script>
    var oTable;
    var id_delete = "";

    function do_download() {
        let kode_fakultas = $('[name=kode_fakultas]').val();
        let kode_prodi = $('[name=kode_prodi]').val();

        if (!kode_fakultas) {
            kode_fakultas = 0;
        }
        if (!kode_prodi) {
            kode_prodi = 0;
        }
        window.open("<?php echo base_url('laporan/data_mahasiswa_download/'); ?>" + kode_fakultas + "/" + kode_prodi);
    }

    function dialog_tambah() {
        $("#modal-download").modal("show");
    }


    function download_modal() {
        $('#myDownload').modal("show");
    }

    let url = '<?php echo site_url('laporan/data_mahasiswa/') ?>' + '/';

    $(document).ready(function() {
        
        var formTitle = $("#modal-form #modal-title").html();
        var formBody = $("#modal-form .modal-body").html();
        var formFooter = $("#modal-form .modal-footer").html();
        $("#modal-form").modal({
            backdrop: "static",
            show: false
        });
       

        oTable = $('#table-datatable').dataTable({
            "ajax": {
                "url": url + $('[name=keterangan_db] option:selected').val(),
                "type": "POST",
            },
            processing: true,
            "sDom": "<'row'<'col-sm-6'><'col-sm-6'>r>t<'row'<'col-sm-6'i><'col-sm-6'p>>",
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "order": [], //Initial no order.
            //Set column definition initialisation properties.
            "columnDefs": [{
                "targets": [0], //first column / numbering column
                "orderable": false, //set not orderable
            }, ],
        });

        $("#field-cari").on('keyup', function(e) {
            var code = e.which;
            if (code == 13) e.preventDefault();
            if (code == 32 || code == 13 || code == 188 || code == 186) {
                oTable.fnFilter($("#field-cari").val().trim());
            }
        });
        $("#btn-cari").click(function() {
            oTable.fnFilter($("#field-cari").val().trim());
        });

        // keterangan = $('[name=keterangan_db] option:selected').text();
        // oTable.api().ajax.url(url + keterangan).load();


        $("#btn-filter-batal").click(function() {

            var NTypSource = '<?php echo base_url('pendaftar'); ?>';
            oTable.api().ajax.url(NTypSource).load();
        });


        $('.fakultas').change(function(event, a) {
            //alert($(this).val());
            $('.prodi').html("<option>Loading...</option>");
            $.ajax({
                url: "<?php echo base_url(); ?>laporan/get_prodi",
                type: 'POST',
                data: {
                    'fakultas': $(this).val()
                },
                success: function(data) {
                    $('.prodi').html(data);
                    if (a != null)
                        $('.prodi option[value=' + a + ']').prop('selected', true);
                    else
                        $('.prodi option[value=<?php echo set_value('kode_prodi', 0) ?>]').prop('selected', true);
                }
            });
        });

        
    });
</script>