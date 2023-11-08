 <!-- BEGIN PAGE CONTAINER-->
 <div class="page-content">
    <!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->
    <div class="content">
       <div class="page-title col-lg-8 col-md-8 col-sm-8 col-xs-8">
          <i class="fa fa fa-server"></i>
          <h3><span class="semi-bold">Role</span></h3>
       </div>
       <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
          <a href="#" data-toggle="modal" data-backdrop="static" data-target="#modal-form" class="btn btn-primary btn-cons" style="float : right ; margin-right : -14px"><i class="fa fa-plus ico-space"></i></i><span>Tambah Data</span></a>
       </div>
       <div class="page-title col-lg-12 col-md-12  hidden-md hidden-xs hidden-sm">
          <i class="fa fa-info-circlee"></i>
          <span style="font-size : 14pt ;">Role pengguna pada SIREMA</span>
       </div>
<!--       <div class="col-lg-4 col-md-4">
          <a href="#" data-toggle="modal" data-backdrop="static" data-target="#modal-form" class="btn btn-primary btn-cons" style="float : right ; margin-right : -14px"><i class="fa fa-plus ico-space"></i></i><span>Tambah Data</span></a>
       </div>-->
       <div class="row-fluid">
          <div class="span12">
             <div class="grid simple ">
                <div class="grid-title">
                   <h4>Tabel <span class="semi-boldd">Role Pengguna</span></h4>
                   <div class="tools">
                      <a href="javascript:;" class="collapse"></a>
                   </div>
                   
                </div>
                <div class="grid-body ">
                   <table class="table table-hover table-condensed" id="table-role">
                      <thead>
                         <tr>
                          <th>No</th>
                          <th>Role</th>
                          <th width="auto%">Opsi</th>
                         </tr>
                      </thead>
                    </table>
                </div>
             </div>
          </div>

       </div>

    </div>
    
    
    
 </div>
<!--<div class="col-md-4 pull-right">
      <div class="input-group" >
          <input  type="text" id="field-cari" class="form-control" name="field-cari" placeholderr="Pencarian">
          <span class="input-group-btn" >
              <a class="btn btn-primary" id="btn-cari" href="#" value="Cari"><i class="fa fa-search"></i></a>
          </span>
      </div>
  </div>-->
                  
           <!--Modal Hapus-->
<div id="modal-hapus" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header"><span class="semi-boldd">Hapus Role</span></div>
            <div class="modal-body">
                Anda yakin menghapus Role "<span id="id-delete"></span>"?
            </div>
            <div class="modal-footer2" style="text-align: center;padding-bottom: 20px;">
                <button id="btn-hapus" class="btn btn-danger" onclick="hapus();">
                    <span class="fa fa-spinner fa-spin"></span> Hapus
                </button>
                <button id="btn-batal" data-dismiss="modal" class="btn btn-primary">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!--Modal Form -->
<div id="modal-form" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" ><h5 id="modal-title">Tambah Role</h5></div>
            <div class="modal-body">
                <div class="alert alert-danger" id="pesan-error"></div>
                <?php $this->view('_form'); ?>
            </div>
            <div class="modal-footer">
                <button id="btn-simpan" class="btn btn-primary modalbtn" onclick="simpan();">
                    <span class="fa fa-spinner fa-spin"></span> Simpan
                </button>
                <button id="btn-batal-simpan" data-dismiss="modal" class="btn btn-danger modalbtn">Tutup</button>
            </div>
        </div>
    </div>
</div>

</section>



<script>
    var oTable;
    function setModalHapus(dom) {
        var id = dom.data('id');
        $(".fa-spinner").hide();
        $("#btn-hapus").show();
        $("#btn-batal").html("Batal");
        $("#modal-hapus .modal-body").html("Anda yakin menghapus Role \"<span id='id-delete'></span>\"?");
        $("#id-delete").html(id);
    }
    function hapus() {
        var id = $("#id-delete").html();
        $.ajax({
            url: "<?php echo base_url('role/hapus'); ?>",
            data: {'id': id},
            dataType: 'JSON',
            beforeSend: function () {
                $(".fa-spinner").show();
                $("#btn-hapus").attr("disabled", true);
                $("#btn-batal").attr("disabled", true);
            },
            success: function (data) {
                $(".fa-spinner").hide();
                $("#btn-hapus").removeAttr("disabled");
                $("#btn-batal").removeAttr("disabled");
                if (data.hapus) {
                    $("#btn-hapus").hide();
                    $("#btn-batal").html("Tutup");
                    oTable.fnDraw();
                }
                $("#modal-hapus .modal-body").html(data.pesan);
            },
        });
    }
    function simpan() {
        var data = $("#form-simpan").serialize();
        var id = $("#kode").val();
        $.ajax({
            url: "<?php echo base_url("role/simpan"); ?>/" + id,
            data: data,
            type: "POST",
            dataType: 'JSON',
            beforeSend: function () {
                $(".fa-spinner").show();
                $("#btn-simpan").attr("disabled", true);
                $("#btn-batal").attr("disabled", true);
            },
            success: function (data) {
                $(".fa-spinner").hide();
                $("#btn-simpan").removeAttr("disabled");
                $("#btn-batal").removeAttr("disabled");
                if (data.simpan) {
                    oTable.fnDraw();
                    $("#btn-simpan").hide();
                    $("#btn-batal-simpan").html("Tutup");
                    $("#modal-form .modal-body").html(data.pesan);
                } else {
                    $("#pesan-error").show();
                    $("#pesan-error").html(data.pesan);
                }
            }
        });
    }
    function edit(obj) {
        var id = obj.data('id');
        $.ajax({
            url: "<?php echo base_url('role/simpan'); ?>/" + id,
            data: id,
            type: "GET",
            dataType: 'JSON',
            beforeSend: function () {
                $("#modal-form").modal('show');
                $("#modal-form #modal-title").html("Perbaharui Role");
                $(".fa-spinner").show();
                $("#btn-simpan").attr("disabled", true);
            },
            success: function (data) {
                if (data.simpan) {
                    $.each(data.model, function (key, value) {
                        $("#" + key).val(value);
                    });
                    $("#kode").val(data.model.role);
                    $(".fa-spinner").hide();
                    $("#btn-simpan").removeAttr("disabled");
                } else {
                    $("#modal-form .form-body").html(data.pesan);
                }
            }
        });
    }


    $(document).ready(function () {
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
        oTable = $('#table-role').dataTable({
            processing: true,
            serverSide: true,
            scrollX : false, 
            pagingType : 'numbers',
            ajax: "<?php echo base_url('role'); ?>",
            lengthMenu: [10, 20, 30],
            dom: '<"top">lrt<"bottom"p>',
            columns: [
                {render : function(data,type,row,meta){
                    return meta.row+1;
		},searchable:false,orderable:false,width:"17px"},
                {data: 'role'},
                {data: 'role', searchable: false, orderable: false,
                    render: function (data,type,row) {
                        var edit = "<a data-id=" + data + " style='margin :0px 1px 0px 0px ; color : #f0ad4e  ;' onclick='edit($(this));return false;' title='Ubah'><span class='ico-space'><i class='fa fa-pencil' aria-hidden='true'></i></span></a> ";
                        var hapus = "<a data-id='" + data + "' style='margin :0px 0px 0px 0px ; color : #d9534f ;' data-backdrop='static' data-toggle='modal' data-target='#modal-hapus' onclick='return setModalHapus($(this));' href='#' title='Hapus'><span class='ico-space'><i class='fa fa-eraser' aria-hidden='true'></i></span></a> ";
                    if (data == 'superadmin') return '';
                        else return edit + hapus;
                    }
                },
            ]
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
    });
</script>
