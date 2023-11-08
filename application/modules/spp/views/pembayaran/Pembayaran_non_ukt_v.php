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
            <i class="fa fa-address-book-o"></i>
            <h3><span class="semi-bold">Pembayaran Non-UKT</span><span style="font-size : 11pt ;"> >> Daftar Pembayaran</span></h3>
        </div>
        <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
            <div class="dropdown" style="float : right ; margin-right : -14px">
                <?php if ($this->session->userdata('user')['role'] == "keuangan_pasca") {
                    $url = base_url('spp/pembayaran/download_xls/' . $kode_periode) . '/Cek Plagiasi';
                } else {
                    $url = base_url('spp/pembayaran/download_xls/' . $kode_periode) . '/nonukt';
                } ?>
                <a class="btn btn-primary" href="<?php echo $url; ?>"> <span class="fa fa-download"></span> Unduh XLS</a>
            </div>
        </div>
        <div class="page-title col-lg-8 col-md-8 hidden-md hidden-xs hidden-sm">
            <i class="fa fa-info-circlee"></i>
        </div>
        <div class="row">
            <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
                <div class="nopad col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <label class="control-label" style="margin-top:10px">Keterangan Tagihan</label>
                </div>
                <div class="nopad col-lg-9 col-md-4 col-sm-4 col-xs-12">
                    <?php
                    if ($this->session->userdata('user')['role'] == "keuangan_pasca") {
                        $ref_jnsKode = array("Cek Plagiasi" => "Cek Plagiasi");
                    } else {
                        $ref_jnsKode = array("nonukt" => "Semua", "Tes Psikologi" => "Tes Psikologi", "Tes Kesehatan" => "Tes Kesehatan", "Tes Bakat" => "Tes Bakat", "91" => "Admisi S1", "92" => "Profesi", "93" => "Pasca");
                    }
                    echo form_dropdown('keterangan_db', $ref_jnsKode, set_value('keterangan_db'), array('class' => 'chosen-select form-select drop_select', 'style' => 'width:100%')); ?>
                </div>
            </div>
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
                        <h4>Daftar Pembayaran Non-UKT <?php echo $label_nama_periode . $label_jenjang; ?></h4>
                        <div class="tools">
                            <a href="javascript:;" class="collapse"></a>
                        </div>
                    </div>
                    <div class="grid-body ">
                        <table class="table table-hover table-condensed" id="table-datatable" style="font-size:8px" width='100%'>
                            <thead>
                                <tr>
                                    <th width="1%">NO</th>
                                    <th width="15%">NIM</th>
                                    <th width="22%">NAMA</th>
                                    <th width="15%">JUMLAH</th>
                                    <th width="10%">WAKTU</th>
                                    <th width="15%">KANAL</th>
                                    <th width="15%">BANK</th>
                                    <th width="15%">KETERANGAN</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="myDetail" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="modal-title">Detail Tagihan</h5>
            </div>
            <div class="modal-body detail">
            </div>
            <div class="modal-footer">
                <button id="btn-batal-simpan" data-dismiss="modal" class="btn modalbtn">Tutup</button>
            </div>
        </div>
    </div>
</div>

<link href="<?php echo base_url(); ?>assets/css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
<script>
    var oTable;
    var id_delete = "";

    let url = '<?php echo site_url('spp/pembayaran/ajax_list/' . $kode_periode) ?>' + '/';

    function detail(id) {
        $('#myDetail').modal("show");
        $.ajax({
            url: "<?php echo base_url('spp/bypasspembayaran/detail/'); ?>" + id,
            type: "GET",
            success: function(data) {
                $(".detail").html(data);
            }
        });
    }

    $(document).ready(function() {

        $('[name=keterangan_db]').change(function(event, a) {
            keterangan = $('[name=keterangan_db] option:selected').val();
            oTable.api().ajax.url(url + keterangan).load();
            console.log(url);
        });

        oTable = $('#table-datatable').dataTable({
            "ajax": {
                "url": url + $('[name=keterangan_db] option:selected').val(),
                "type": "POST",
            },
            processing: true,
            pagingType: 'numbers',
            scrollX: false,
            bFilter: true,
            bLengthChange: false,
            dom: '<"top">lrt<"bottom"ip>',
            columnDefs: [{
                "className": "dt",
                "targets": [3]
            }],
        });


        $("#field-cari").on('keyup', function(e) {
            var code = e.which;
            oTable.fnFilter($("#field-cari").val().trim());
            if (code == 13) e.preventDefault();
        });
        $("#btn-cari").click(function() {
            oTable.fnFilter($("#field-cari").val());
        });

    });
</script>