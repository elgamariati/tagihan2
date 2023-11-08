 <!-- BEGIN PAGE CONTAINER-->
 <div class="page-content">
    <!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->
    <div class="content">
       <div class="page-title col-lg-8 col-md-8 col-sm-8 col-xs-8">
          <i class="fa fa fa-server"></i>
          <h3><span class="semi-bold">Program Studi</span><span style="font-size : 11pt ;"> Data referensi program studi.</span></h3>
       </div>
       
      
<!--       <div class="col-lg-4 col-md-4">
          <a href="#" data-toggle="modal" data-backdrop="static" data-target="#modal-form" class="btn btn-primary btn-cons" style="float : right ; margin-right : -14px"><i class="fa fa-plus ico-space"></i></i><span>Tambah Data</span></a>
       </div>-->
       <div class="row-fluid">
          <div class="span12">
             <div class="grid simple ">
                <div class="grid-title">
                   <h4>Tabel <span class="semi-boldd">Program Studi</span></h4>
                   <div class="tools">
                      <a href="javascript:;" class="collapse"></a>
                   </div>
                   
                </div>
                <div class="grid-body ">
					<div class="row">
                        <div class="col-md-4 pull-right">
                            <div class="input-group" >
                                <input  type="text" id="field-cari" class="form-control" name="field-cari">
                                <span class="input-group-btn" >
                                    <!--<input type="button" id="btn-cari" class="btn btn-default" value="Cari" />-->
                                    <a class="btn btn-primary" id="btn-cari" href="#" value="Cari"><i class="fa fa-search"></i></a>
                                </span>
                            </div>
                        </div>
                    </div>
                   <table class="table table-hover table-condensed" id="table-jalur">
                      <thead>
                         <tr>
                          <th>NO</th>
                          <th>KODE SIA</th>
                          <th>NAMA PRODI SIA</th>
                          <th>FAKULTAS</th>
                          <th>KODE NIM</th>
                          <th width="auto%">AKSI</th>
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
            <div class="modal-header"><span class="semi-boldd">Hapus Jalur</span></div>
            <div class="modal-body">
                Anda yakin menghapus Jalur dengan Kode "<span id="id-delete"></span>"?
            </div>
            <div class="modal-footer2" style="text-align: center;padding-bottom: 20px;">
                <button id="btn-hapus" class="btn btn-primary" onclick="hapus();">
                    <span class="fa fa-spinner fa-spin"></span> Hapus
                </button>
                <button id="btn-batal" data-dismiss="modal" class="btn">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!--Modal Form -->
<div id="modal-form" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" ><h5 id="modal-title">Edit Kode NIM</h5></div>
            <div class="modal-body">
               
                <?php $this->view('_form'); ?>
				
            </div>
            <div class="modal-footer">
                <button id="btn-simpan" class="btn btn-primary modalbtn" onclick="simpan();">
                    <span class="fa fa-spinner fa-spin"></span> <i class="fa fa-floppy-o" aria-hidden="true"></i> Simpan
                </button>
                <button id="btn-batal-simpan" data-dismiss="modal" class="btn modalbtn">Tutup</button>
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
        $("#modal-hapus .modal-body").html("Anda yakin menghapus Jalur dengan Kode \"<span id='id-delete'></span>\"?");
        $("#id-delete").html(id);
    }
    function hapus() {
        var id = $("#id-delete").html();
        $.ajax({
            url: "<?php echo base_url('jalur/hapus'); ?>",
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
		if ($("#prodiId").val()!=""){
			var data = $("#form-simpan").serialize();
			var id = $("#kode").val();
			$.ajax({
				url: "<?php echo base_url("prodi_ref/simpan"); ?>/" + id,
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
						$("#modal-form .modal-body").html("<div class='alert alert-success'>"+data.pesan+"</div>");
					} else {
						$("#pesan-error").show();
						$("#pesan-error").html("<div class='alert alert-danger'>"+data.pesan+"</div>");
					}
				}
			});
		}
    }
    function edit(obj) {
        var id = obj.data('id');
        $.ajax({
            url: "<?php echo base_url('prodi_ref/simpan'); ?>/" + id,
            data: id,
            type: "GET",
            dataType: 'JSON',
            beforeSend: function () {
                $("#modal-form").modal('show');
                $("#modal-form #modal-title").html("Perbaharui Kode NIM");
                $(".fa-spinner").show();
                $("#btn-simpan").attr("disabled", true);
            },
            success: function (data) {
                if (data.simpan) {
                    $.each(data.model, function (key, value) {
                        $("#" + key).val(value);
                    });
                    $("#kode").val(data.model.prodiKode);
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
        oTable = $('#table-jalur').dataTable({
            processing: true,
            serverSide: true,
            scrollX : false, 
            pagingType : 'numbers',
            ajax: "<?php echo base_url('prodi_ref'); ?>",
            lengthMenu: [10, 20, 30],
            dom: '<"top">lrt<"bottom"p>',
			"order": [[ 4, "asc" ]],
            columnDefs: [{"className": "dt-tengah", "targets": [3]}],
            columns: [
                {render : function(data,type,row,meta){
                    var length=meta.settings._iDisplayStart;
                    return meta.row+length+1;
		},searchable:false,orderable:false,width:"17px"},
                {data: 'prodiKode',name:'sia_m_prodi.prodiKode'},
                {data: 'prodiNamaResmi',name:'sia_m_prodi.prodiNamaResmi'},
                {data: 'fakNamaResmi'},
                {data: 'prodiId'},
                {data: 'prodiKode',name:'sia_m_prodi.prodiKode', searchable: false, orderable: false,
                    render: function (data,type,row) {
                        var edit = "<a data-id=" + data + " style='margin :0px 1px 0px 0px ;' onclick='edit($(this));return false;' title='Ubah'><span class='ico-space'><i class='fa fa-pencil' aria-hidden='true'></i></span></a> ";
                        var hapus = "<a data-id='" + data + "' style='margin :0px 0px 0px 0px ; color : #d9534f ;' data-backdrop='static' data-toggle='modal' data-target='#modal-hapus' onclick='return setModalHapus($(this));' href='#' title='Hapus'><span class='ico-space'><i class='fa fa-eraser' aria-hidden='true'></i></span></a> ";
                        return edit;
                    }
                },
            ]
        });
        $("#field-cari").on('keyup', function(e) {
            var code = e.which;
            if(code==13)e.preventDefault();
            if(code==32||code==13||code==188||code==186){
                oTable.fnFilter($("#field-cari").val());
            }
        });
        $("#btn-cari").click(function () {
            oTable.fnFilter($("#field-cari").val());
        });
    });
</script>
