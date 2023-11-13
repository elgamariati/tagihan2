 <!-- BEGIN PAGE CONTAINER-->
 <div class="page-content">
     <!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->
     <div class="content">
         <div class="page-title col-lg-8 col-md-8 col-sm-8 col-xs-8">
             <i class="fa fa fa-server"></i>
             <h3><span class="semi-bold">Aturan Cicilan</span><span style="font-size : 11pt ;"> Daftar Aturan Cicilan</span></h3>
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
                         <h4>Tabel <span class="semi-boldd">Aturan Cicilan</span></h4>
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
                                     <th>Nama</th>
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