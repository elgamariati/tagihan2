<style>
    .nopad {
        padding: 0 0 0 0;
    }
</style>
<!-- BEGIN PAGE CONTAINER-->
<div class="page-content">
    <!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->
    <div class="content">
        <div class="page-title col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <i class="fa fa-address-book-o"></i>
            <h3><span class="semi-bold">Hak Akses</span><span style="font-size : 11pt ;"> >> Master</span></h3>
        </div>
        <div class=" row">
            &nbsp
        </div>

        <div class="row">
            <div class="col-md-4 pull-right">
                <div class="input-group">
                    <input type="text" id="field-cari" class="form-control" name="field-cari">
                    <span class="input-group-btn">
                        <a class="btn btn-primary" id="btn-cari" href="#" value="Cari"><i class="fa fa-search"></i></a>
                    </span>
                </div>
                <br>
            </div>
        </div>
        <div class="row-fluid">
            <div class="span12">
                <div class="grid simple ">
                    <div class="grid-title">
                        <h4>Hak Akses</h4>
                        <div class="tools">
                            <a href="javascript:;" class="collapse"></a>
                        </div>
                    </div>
                    <div class="grid-body ">
                        <div class="col-md-12 nopad">
                            <ul class="nav nav-tabs" role="tablist">
                                <?php
                                $i = 0;
                                foreach ($user_role as $ur) {
                                    $class = "";
                                    if ($i == 0) {
                                        $class = "active";
                                    }  ?>
                                    <li class="<?php echo $class ?>">
                                        <a href="#tab_user_role" id="tab_user_role" role="tab" data-toggle="tab" aria-expanded="false" name="<?php echo $ur->role ?>">
                                            <center><?php echo $ur->roleNama ? $ur->roleNama : "-" ?></center>
                                        </a>
                                    </li>
                                <?php
                                    $i++;
                                } ?>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="tab1hellowWorld">
                                    <div class="row column-seperation">
                                        <div class="col-md-12 nopad">
                                            <table class="table table-hover table-condensed" id="table" style="font-size:8px" width='100%'>
                                                <thead>
                                                    <tr>
                                                        <th width="30%">ROLE</th>
                                                        <th width="30%">NAMA MODUL </th>
                                                        <th width="40%">HAK AKSES </th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</div>
</section>
<link href="<?php echo base_url(); ?>assets/css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootstrap-datetimepicker.js" charset="UTF-8"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootstrap-datetimepicker.id.js" charset="UTF-8"></script>
<script>
    function ha_change(object) {
        var form_data = new FormData();
        form_data.append("role", key);
        form_data.append("modul", object.value);
        form_data.append("hak", object.name);
        $.ajax({
            url: "<?php echo base_url($modul . '/action/') ?>",
            type: "POST",
            data: form_data,
            processData: false,
            contentType: false,
            async: false,
            dataType: 'json',
            success: function(data) {
                console.log(data);
                if (data.status == "validasi") {
                    toastr.error(data.keterangan);
                    $(object).trigger('click');
                } else {
                    // toastr.success(data.keterangan);
                }
                oTable.api().ajax.reload();
            },
            error: function() {
                toastr.success("Terjadi sebuah kesalahan");
            }
        })
    }

    let key = "<?php echo $user_role[0] ? $user_role[0]->role : ''  ?>";
    var url = "<?php echo base_url($modul . '/ajax_list') ?>";
    var myData = {};

    $(document).ready(function() {
        //datatables

        // set otable ajax request post data
        myData.key = key;

        oTable = $('#table').dataTable({
            processing: true, //Feature control the processing indicator.
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "order": [], //Initial no order.
            // Load data for the table's content from an Ajax source
            "ajax": {
                "url": url,
                "type": "POST",
                "data": function(d) {
                    return $.extend(d, myData);
                }
            },
            "sDom": "lrtip",
            // "searching": false,
            //Set column definition initialisation properties.
            "columnDefs": [{
                "targets": [0], //first column / numbering column
                "orderable": false, //set not orderable
            }, ],
            "fnDrawCallback": function(oSettings) {
                console.log("drawed");
                console.log(key);
                // $('#table-datatable').show();
            },
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

        $("[id=tab_user_role]").click(function() {
            key = $(this)[0].name;
            myData.key = key;
            oTable.api().ajax.reload();
        });
    });
</script>