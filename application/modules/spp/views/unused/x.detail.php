<!-- BEGIN PAGE CONTAINER-->
<style>
    .a{
        width: 30%;
    }
    
</style>
<div class="page-content">
    <!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->
    <div class="content">
        <div class="page-title col-lg-8 col-md-8 col-sm-8 col-xs-8">
            <i class="fa fa-address-book-o"></i>
            <h3><span class="semi-bold">Detail Pendaftar</span></h3>
        </div>
        <div class="row-fluid">
            <div class="span12">
                <div class="row">
                    <div class="col-md-12">
                        <div class="grid simple">
                            <div class="grid-title no-border">
                                <h4><?php echo $model['regNopes']; ?> <span class="semi-boldd"><?php echo " - ".$model['regNama']; ?></span></h4>
                                <div class="tools">
                                    <a href="javascript:;" class="collapse"></a>
                                </div>
                            </div>
                            <div class="grid-body no-border">
                                <div class="box-body">
                                    <div class="row" style="margin: 0px;">                         
                                        <table style="width:100%" class="table table-hover table-bordered">                    
                                            
                                            <tr>
                                                <td class="a">Nomor Peserta</td>
                                                <td><?php echo $model['regNopes']; ?></td>
                                            </tr>

                                            <tr>
                                                <td class="a">Nama Peserta</td>
                                                <td><?php echo $model['regNama']; ?></td>
                                            </tr>

                                            
                                            <tr>
                                                <td class="a">Jalur Masuk</td>
                                                <td><?php echo $model['daftarJalur']; ?></td>
                                            </tr>

                                            <tr>
                                                <td class="a">Program Studi Pilihan Ke-1</td>
                                                <td><?php echo $model['namaProdi1']; ?></td>
                                            </tr>
                                            
                                            <tr>
                                                <td class="a">Program Studi Pilihan Ke-2</td>
                                                <td><?php echo $model['namaProdi2']; ?></td>
                                            </tr>
                                            
                                            <tr>
                                                <td class="a">Program Studi Pilihan Ke-3</td>
                                                <td><?php echo $model['namaProdi3']; ?></td>
                                            </tr>
                                                                                        
                                            <tr>
                                                <td class="a">Penerima Bidikmisi</td>
                                                <td><?php echo $model['regBidikmisi']; ?></td>
                                            </tr>

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
                  