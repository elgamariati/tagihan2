<?php
$periode_aktif = $aktif["kode_periode"];
$link = ""; //$this->sia->encrypt($sem['semaktifSemester']);
$name = $this->router->fetch_class();
$method = $this->router->fetch_method();
$name = $this->router->fetch_class() . "/" . $this->router->fetch_method();

$menu_tagihan = array(
    'spp/formulir',
    'spp/angsuran',
    'spp/formulir_maba',
    'mahasiswa/index',
    'spp/tagihan'
);
$menu_tagihan_non_ukt = array(
    'spp/input_non_ukt',
    'spp/tagihan_non_ukt',
    'pembayaran/daftar_non_ukt'
);
if ($this->session->userdata('user')['role'] == "keuangan_pasca") {
    $menu_tagihan_non_ukt[0] = 'spp/formulir_plagiasi';
}
$menu_pembayaran = array(
    'pembayaran/index',
    'pembayaran/daftar',
);
$menu_pengaturan = array(
    'periode/index',
    'atur_jenjang/index',
    'spp/salin_tagihan',
    'atur_rentang/index',
);
$menu_penihilan  = array(
    'bypasspembayaran/formulir',
    'bypasspembayaran/pembayaran',
);
$menu_laporan  = array(
    'laporan/detail_pembayaran',
    'laporan/detail_tidak_bayar',
    'laporan/mhs_aktif',
);
// if ($this->session->userdata('user')['role'] == "keuangan_pasca") {
$menu_laporan[3] = 'laporan/detail_pembayaran_admisi';
// }
$menu_laporan[4] = 'laporan/keringanan_ukt';
$menu_laporan[5] = 'laporan/data_mahasiswa';

$menu_acl  = array(
    'modul/index',
    'user_role/index',
    'hak_akses/index',
);

?>
<div class="page-container row-fluid">
    <!-- BEGIN SIDEBAR -->
    <div class="page-sidebar" id="main-menu">
        <!-- BEGIN MINI-PROFILE -->
        <div class="page-sidebar-wrapper scrollbar-dynamic" id="main-menu-wrapper">
            <div class="user-info-wrapper">
                <div class="user-info" style="margin-top : 12px">
                    Selamat datang
                    <div class="username" style="font-size: 12pt">
                        <?php echo $this->session->userdata('user')['nama']; ?>
                    </div>
                </div>
            </div>
            <br>
            <p class="menu-title" style="margin-top : 15px">MENU </p>
            <!-- END MINI-PROFILE -->
            <!-- BEGIN SIDEBAR MENU -->
            <?php if ($this->session->userdata('user')['jenjang']) { ?>
                <ul class="top-margin">
                    <li class="<?php echo (in_array($name, $menu_pengaturan)) ? "active open" : ""; ?>">
                        <a href="javascript:;"> <i class="fa fa-gears"></i> <span class="title">Pengaturan</span> <span class="arrow "></span> </a>
                        <ul class="sub-menu">
                            <li class="<?php echo ($name == "periode/index") ? "active" : ""; ?>">
                                <a href=<?php echo base_url('periode/index'); ?>> <i class="fa fa-calendar"></i> <span class="title">Periode Bayar</span></a>
                            </li>
                            <li class="<?php echo ($name == "atur_jenjang/index") ? "active" : ""; ?>">
                                <a href=<?php echo base_url('atur_jenjang/index'); ?>> <i class="fa fa-cogs"></i> <span class="title">Pengaturan Jenjang</span></a>
                            </li>
                            <li class="<?php echo ($name == "spp/salin_tagihan") ? "active" : ""; ?>">
                                <a href=<?php echo base_url('spp/salin_tagihan'); ?>> <i class="fa fa-clone"></i> <span class="title">Salin Tagihan</span></a>
                            </li>
                            <li class="<?php echo ($name == "atur_rentang/index") ? "active" : ""; ?>">
                                <a href=<?php echo base_url('atur_rentang'); ?>> <i class="fa fa-clock-o"></i> <span class="title">Rentang Pembayaran</span></a>
                            </li>
                        </ul>
                    </li>
                    <li class="<?php echo (in_array($name, $menu_tagihan)) ? "active open" : ""; ?>">
                        <a href="javascript:;"> <i class="fa fa-bank"></i> <span class="title">UKT</span> <span class="arrow "></span> </a>
                        <ul class="sub-menu">
                            <li class="<?php echo ($name == 'spp/formulir') ? "active" : ""; ?>">
                                <a href=<?php echo base_url('spp/formulir/1'); ?>> <i class="fa fa-plus-square"></i> <span class="title">Input Tagihan</span></a>
                            </li>
                            <!-- Hide Sementara -->
                            <!-- <li class="<?php echo ($name == "spp/angsuran") ? "active" : ""; ?>">
                            <a href=<?php echo base_url('spp/angsuran/1'); ?>> <i class="fa fa-plus-square"></i> <span class="title">Input Tagihan Angsuran</span></a>
                        </li> -->
                            <li class="<?php echo ($name == 'spp/formulir_maba') ? "active" : ""; ?>">
                                <a href=<?php echo base_url('spp/formulir_maba/1'); ?>> <i class="fa fa-plus-square"></i> <span class="title">Input Tagihan Mahasiswa Baru</span></a>
                            </li>
                            <li class="<?php echo ($name == "spp/tagihan") ? "active" : ""; ?>">
                                <a href=<?php echo base_url('spp/tagihan/1'); ?>> <i class="fa fa-group"></i> <span class="title">Daftar Tagihan</span></a>
                            </li>
                            <li class="<?php echo ($name == "mahasiswa/index") ? "active" : ""; ?>">
                                <a href=<?php echo base_url('spp/mahasiswa'); ?>> <i class="fa fa-user-circle"></i> <span class="title">Tagihan per Mhs</span></a>
                            </li>
                        </ul>
                    </li>
                    <li class="<?php echo (in_array($name, $menu_pembayaran)) ? "active open" : ""; ?>">
                        <a href="javascript:;"> <i class="fa fa-money"></i> <span class="title">UKT Terbayar</span> <span class="arrow "></span> </a>
                        <ul class="sub-menu">
                            <li class="<?php echo ($name == "pembayaran/index") ? "active" : ""; ?>">
                                <a href=<?php echo base_url('spp/pembayaran'); ?>> <i class="fa fa-list-alt"></i> <span class="title">Pembayaran per Mhs</span></a>
                            </li>
                            <li class="<?php echo ($name == "pembayaran/daftar") ? "active" : ""; ?>">
                                <a href=<?php echo base_url('spp/pembayaran/daftar/'); ?>> <i class="fa fa-list-alt"></i> <span class="title">Pembayaran UKT</span></a>
                            </li>
                        </ul>
                    </li>

                    <li class="<?php echo (in_array($name, $menu_tagihan_non_ukt)) ? "active open" : ""; ?>">
                        <a href="javascript:;"> <i class="fa fa-bank"></i> <span class="title">Pembayaran IPI</span> <span class="arrow "></span> </a>
                        <ul class="sub-menu">
                            <?php if ($this->session->userdata('user')['role'] !== "keuangan_pasca") {
                            ?>
                                <li class="<?php echo ($name == $menu_tagihan_non_ukt[0]) ? "active" : ""; ?>">
                                    <a href=<?php echo base_url($menu_tagihan_non_ukt[0]); ?>> <i class="fa fa-plus-square"></i> <span class="title">Input Tagihan IPI</span></a>
                                </li>
                            <?php } else { ?>
                                <li class="<?php echo ($name == $menu_tagihan_non_ukt[0]) ? "active" : ""; ?>">
                                    <a href=<?php echo base_url($menu_tagihan_non_ukt[0]); ?>> <i class="fa fa-plus-square"></i> <span class="title">Input Tagihan Cek Plagiasi</span></a>
                                </li>
                            <?php }
                            ?>
                            <li class="<?php echo ($name == "spp/tagihan_non_ukt" || $name == "spp/tagihan_non_ukt") ? "active" : ""; ?>">
                                <a href=<?php echo base_url('spp/tagihan_non_ukt/'); ?>> <i class="fa fa-group"></i> <span class="title">Daftar Tagihan IPI</span></a>
                            </li>
                            <li class="<?php echo ($name == "pembayaran/daftar_non_ukt") ? "active" : ""; ?>">
                                <a href=<?php echo base_url('spp/pembayaran/daftar_non_ukt/'); ?>> <i class="fa fa-list-alt"></i> <span class="title">Daftar Pembayaran IPI</span></a>
                            </li>
                        </ul>
                    </li>

                    <li class="<?php echo (in_array($name, $menu_penihilan)) ? "active open" : ""; ?>">
                        <a href="javascript:;"> <i class="fa fa-user-times"></i> <span class="title">Penihilan Bayar</span> <span class="arrow "></span> </a>
                        <ul class="sub-menu">
                            <li class="<?php echo ($name == "bypasspembayaran/formulir") ? "active" : ""; ?>">
                                <a href=<?php echo base_url('spp/bypasspembayaran/formulir/') ?>> <i class="fa fa-eraser"></i> <span class="title">Penihilan</span></a>
                            </li>
                            <li class="<?php echo ($name == "bypasspembayaran/pembayaran") ? "active" : ""; ?>">
                                <a href=<?php echo base_url('spp/bypasspembayaran/pembayaran/'); ?>> <i class="fa fa-list"></i> <span class="title">Daftar Penihilan</span></a>
                            </li>
                        </ul>
                    </li>


                    <li class="<?php echo (in_array($name, $menu_laporan)) ? "active open" : ""; ?>">
                        <a href="javascript:;"> <i class="fa fa-file"></i> <span class="title">Laporan</span> <span class="arrow "></span> </a>
                        <ul class="sub-menu">
                            <li class="<?php echo ($name == $menu_laporan[0]) ? "active" : ""; ?>">
                                <a href=<?php echo base_url($menu_laporan[0] . '/' . $periode_aktif . '/' . 'UKT') ?>> <i class="fa fa-file"></i> <span class="title">Pembayaran</span></a>
                            </li>
                            <li class="<?php echo ($name == $menu_laporan[4]) ? "active" : ""; ?>">
                                <a href=<?php echo base_url($menu_laporan[4] . '/' . $periode_aktif . '/' . 'UKT') ?>> <i class="fa fa-file"></i> <span class="title">Keringanan UKT</span></a>
                            </li>
                            <?php //if ($this->session->userdata('user')['role'] == "keuangan_pasca") {
                            ?>
                            <li class="<?php echo ($name == $menu_laporan[3]) ? "active" : ""; ?>">
                                <a href=<?php echo base_url($menu_laporan[3] . '/' . $periode_aktif . '/92'); ?>> <i class="fa fa-file"></i> <span class="title">Pembayaran Admisi</span></a>
                            </li>
                            <?php //}
                            ?>
                            <li class="<?php echo ($name == $menu_laporan[1]) ? "active" : ""; ?>">
                                <a href=<?php echo base_url($menu_laporan[1] . '/'); ?>> <i class="fa fa-file"></i> <span class="title">Mahasiswa Tidak Bayar</span></a>
                            </li>
                            <li class="<?php echo ($name == $menu_laporan[2]) ? "active" : ""; ?>">
                                <a href=<?php echo base_url($menu_laporan[2] . '/'); ?>> <i class="fa fa-file"></i> <span class="title">Mahasiswa Aktif</span></a>
                            </li>
                            <li class="<?php echo ($name == $menu_laporan[5]) ? "active" : ""; ?>">
                                <a href=<?php echo base_url($menu_laporan[5] . '/'); ?>> <i class="fa fa-file"></i> <span class="title">Data Mahasiswa </span></a>
                            </li>
                        </ul>
                    </li>

                    <li class="<?php echo (in_array($name, $menu_penihilan)) ? "active open" : ""; ?>">
                        <a href=<?php echo base_url('cicilan/') ?>> <i class="fa fa-bars"></i> <span class="title">Aturan Cicilan</span> <span class="arrow "></span> </a>
                    </li>

                    <?php if ($this->session->userdata('user')['role'] !== "superadmin") {
                    ?>
                        <li class="<?php echo (in_array($name, $menu_acl)) ? "active open" : ""; ?>">
                            <a href="javascript:;"> <i class="fa fa-home"></i> <span class="title">Access-control List</span> <span class="arrow "></span> </a>
                            <ul class="sub-menu">
                                <li class="<?php echo ($name == $menu_acl[0]) ? "active" : ""; ?>">
                                    <a href=<?php echo base_url("acl/" . $menu_acl[0]) ?>> <i class="fa fa-book"></i> <span class="title">Modul</span></a>
                                </li>
                                <li class="<?php echo ($name == $menu_acl[1]) ? "active" : ""; ?>">
                                    <a href=<?php echo base_url("acl/" . $menu_acl[1]) ?>> <i class="fa fa-user"></i> <span class="title">Role Pengguna</span></a>
                                </li>
                                <li class="<?php echo ($name == $menu_acl[2]) ? "active" : ""; ?>">
                                    <a href=<?php echo base_url("acl/" . $menu_acl[2]) ?>> <i class="fa fa-shield"></i> <span class="title">Hak Akses</span></a>
                                </li>
                            </ul>
                        </li>
                    <?php } ?>


                </ul>
            <?php } else { ?>
                <ul class="top-margin">
                    <li class="<?php echo (in_array($name, $menu_pengaturan)) ? "active open" : ""; ?>">
                        <a href="javascript:;"> <i class="fa fa-gears"></i> <span class="title">Pengaturan</span> <span class="arrow "></span> </a>
                        <ul class="sub-menu">
                            <li class="<?php echo ($name == "atur_jenjang/index") ? "active" : ""; ?>">
                                <a href=<?php echo base_url('atur_jenjang/index'); ?>> <i class="fa fa-cogs"></i> <span class="title">Pengaturan Jenjang</span></a>
                            </li>

                        </ul>
                    </li>
                </ul>

            <?php } ?>

            <!-- END SIDEBAR MENU -->
        </div>
    </div>
    <!-- END SIDEBAR -->