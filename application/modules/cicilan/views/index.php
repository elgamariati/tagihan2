 <!-- BEGIN PAGE CONTAINER-->
 <div class="page-content">
     <!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->
     <div class="content">
         <div class="page-title col-lg-8 col-md-8 col-sm-8 col-xs-8">
             <i class="fa fa fa-server"></i>
             <h3><span class="semi-bold">Useraaaaaaaaaaaaaaaaaa</span><span style="font-size : 11pt ;"> Manajemen user dan password aplikasi.</span></h3>
         </div>
         <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
             <a href="#" data-toggle="modal" data-backdrop="static" data-target="#modal-form" class="btn btn-primary btn-cons" style="float : right ; margin-right : -14px"><i class="fa fa-plus ico-space"></i></i><span>Tambah Data</span></a>
         </div>

         <!--       <div class="col-lg-4 col-md-4">
          <a href="#" data-toggle="modal" data-backdrop="static" data-target="#modal-form" class="btn btn-primary btn-cons" style="float : right ; margin-right : -14px"><i class="fa fa-plus ico-space"></i></i><span>Tambah Data</span></a>
       </div>-->
         <div class="row-fluid">
             <div class="span12">
                 <div class="grid simple ">
                     <div class="grid-title">
                         <h4>Tabel <span class="semi-boldd">User</span></h4>
                         <div class="tools">
                             <a href="javascript:;" class="collapse"></a>
                         </div>

                     </div>
                     <div class="grid-body ">
                         <div class="row">
                             <div class="col-md-4 pull-right">
                                 <div class="input-group">
                                     <input type="text" id="field-cari" class="form-control" name="field-cari">
                                     <span class="input-group-btn">
                                         <!--<input type="button" id="btn-cari" class="btn btn-default" value="Cari" />-->
                                         <a class="btn btn-primary" id="btn-cari" href="#" value="Cari"><i class="fa fa-search"></i></a>
                                     </span>
                                 </div>
                             </div>
                         </div>
                         <table class="table table-hover table-condensed" id="table-user">
                             <thead>
                                 <tr>
                                     <th>No</th>
                                     <th>Username</th>
                                     <th>Nama</th>
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
             <div class="modal-header"><span class="semi-boldd">Hapus User</span></div>
             <div class="modal-body">
                 Anda yakin menghapus User dengan Username "<span id="id-delete"></span>"?
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
             <div class="modal-header">
                 <h5 id="modal-title">Tambah User</h5>
             </div>
             <div class="modal-body">
                 <div class="alert alert-danger" id="pesan-error"></div>
                 <?php $this->view('_form', array('listRole' => $listRole)); ?>
             </div>
             <div class="modal-footer">
                 <button id="btn-simpan" class="btn btn-primary modalbtn" onclick="simpan();">
                     <span class="fa fa-spinner fa-spin"></span> <span class="fa fa-floppy-o"></span> Simpan
                 </button>
                 <button id="btn-batal-simpan" data-dismiss="modal" class="btn modalbtn">Tutup</button>
             </div>
         </div>
     </div>
 </div>

 <!--Modal Reset-->
 <div id="modal-reset" class="modal fade" tabindex="-1" role="dialog">
     <div class="modal-dialog modal-sm">
         <div class="modal-content">
             <div class="modal-header">Reset Password</div>
             <div class="modal-body">
                 Anda akan mereset password dengan username
                 "<span id="user-reset"></span>" ?
             </div>
             <div class="modal-footer2" style="text-align: center">
                 <button id="btn-reset" class="btn btn-success" onclick="reset();">
                     <span class="fa fa-spinner fa-spin"></span> Reset
                 </button>
                 <button id="btn-batalR" data-dismiss="modal" class="btn btn-default">Tutup</button>
             </div>
         </div>
     </div>
 </div>


 <script>
     var oTable;

     function reset(dom) {
         var user = $("#user-reset").html();
         $.ajax({
             url: "<?php echo base_url('user/reset'); ?>",
             data: {
                 'id': user
             },
             dataType: 'JSON',
             beforeSend: function() {
                 $(".fa-spinner").show();
                 $("#btn-reset").attr("disabled", true);
                 $("#btn-batalR").attr("disabled", true);
             },
             success: function(data) {
                 $(".fa-spinner").hide();
                 $("#btn-reset").removeAttr("disabled");
                 $("#btn-batalR").removeAttr("disabled");
                 if (data.reset) {
                     $("#btn-reset").hide();
                     $("#btn-batalR").html("Tutup");
                 }
                 $("#modal-reset .modal-body").html(data.pesan);
             },
         });
     }

     function setModalReset(dom) {
         var user = dom.data('user');
         $(".fa-spinner").hide();
         $("#btn-reset").show();
         $("#btn-batalR").html("Batal");
         $("#modal-reset .modal-body").html("Anda akan mereset password dengan username \"<span id='user-reset'></span>\" ?");
         $("#user-reset").html(user);
     }

     function setModalHapus(dom) {
         var id = dom.data('id');
         $(".fa-spinner").hide();
         $("#btn-hapus").show();
         $("#btn-batal").html("Batal");
         $("#modal-hapus .modal-body").html("Anda yakin menghapus User dengan Username \"<span id='id-delete'></span>\"?");
         $("#id-delete").html(id);
     }

     function hapus() {
         var id = $("#id-delete").html();
         $.ajax({
             url: "<?php echo base_url('user/hapus'); ?>",
             data: {
                 'id': id
             },
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
             url: "<?php echo base_url("user/simpan"); ?>/" + id,
             data: data,
             type: "POST",
             dataType: 'JSON',
             beforeSend: function() {
                 $(".fa-spinner").show();
                 $("#btn-simpan").attr("disabled", true);
                 $("#btn-batal").attr("disabled", true);
             },
             success: function(data) {
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
             url: "<?php echo base_url('user/simpan'); ?>/" + id,
             data: id,
             type: "GET",
             dataType: 'JSON',
             beforeSend: function() {
                 $("#modal-form").modal('show');
                 $("#modal-form #modal-title").html("Perbaharui Username");
                 $(".fa-spinner").show();
                 $("#btn-simpan").attr("disabled", true);
                 $("#group-password").hide();
                 $("#group-ulangpassword").hide();
             },
             success: function(data) {
                 if (data.simpan) {
                     $.each(data.model, function(key, value) {
                         $("#" + key).val(value);
                     });
                     $("#kode").val(data.model.username);
                     $(".fa-spinner").hide();
                     $("#btn-simpan").removeAttr("disabled");
                 } else {
                     $("#modal-form .form-body").html(data.pesan);
                 }
             }
         });
     }


     $(document).ready(function() {
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
         oTable = $('#table-user').dataTable({
             processing: true,
             serverSide: true,
             scrollX: false,
             pagingType: 'numbers',
             ajax: "<?php echo base_url('user'); ?>",
             lengthMenu: [10, 20, 30],
             dom: '<"top">lrt<"bottom"p>',
             columnDefs: [{
                 "className": "dt-tengah",
                 "targets": [3]
             }],
             columns: [{
                     render: function(data, type, row, meta) {
                         return meta.row + 1;
                     },
                     searchable: false,
                     orderable: false,
                     width: "17px"
                 },
                 {
                     data: 'username'
                 },
                 {
                     data: 'nama'
                 },
                 {
                     data: 'role'
                 },
                 {
                     data: 'username',
                     searchable: false,
                     orderable: false,
                     render: function(data, type, row) {
                         var edit = "<a data-id=" + data + " style='margin :0px 1px 0px 0px ;' onclick='edit($(this));return false;' title='Ubah'><span class='ico-space'><i class='fa fa-pencil' aria-hidden='true'></i></span></a> ";
                         var hapus = "<a data-id='" + data + "' style='margin :0px 0px 0px 0px ; ' data-backdrop='static' data-toggle='modal' data-target='#modal-hapus' onclick='return setModalHapus($(this));' href='#' title='Hapus'><span class='ico-space'><i class='fa fa-times' aria-hidden='true'></i></span></a> ";
                         var reset = "<a class='' data-user='" + data + "' data-backdrop='static' data-toggle='modal' data-target='#modal-reset' onclick='return setModalReset($(this));' href='#' title=''><span>Reset Password</span></a> ";
                         return edit + hapus + reset;
                     }
                 },
             ]
         });
         $("#field-cari").on('keyup', function(e) {
             var code = e.which;
             if (code == 13) e.preventDefault();
             if (code == 32 || code == 13 || code == 188 || code == 186) {
                 oTable.fnFilter($("#field-cari").val());
             }
         });
         $("#btn-cari").click(function() {
             oTable.fnFilter($("#field-cari").val());
         });
     });
 </script>