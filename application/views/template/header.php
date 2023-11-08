<?php

?>
<!-- BEGIN HEADER -->
<div class="header navbar navbar-inverse ">
   <!-- BEGIN TOP NAVIGATION BAR -->
   <div class="navbar-inner">
      <div class="header-seperation">
         <ul class="nav pull-left notifcation-center" id="main-menu-toggle-wrapper" style="display:none">
            <li class="dropdown">
               <a id="main-menu-toggle" href="#main-menu">
                  <div class="iconset top-menu-toggle-white"></div>
               </a>
            </li>
         </ul>
         <!-- BEGIN LOGO -->

         <div class="navbar-brand " style="font-size:20px;color:#fff;text-align:center;padding:20px;margin-right:auto;margin-left:auto"><b style="font-weight:900">SI TAGIHAN</b> <b style="font-weight:100">U P R</b></div>


         <!-- END LOGO -->
         <div class="pull-right hidden-lg hidden-md hidden-sm show">
            <ul class="nav quick-section ">
               <li class="quicklinks">
                  <a data-toggle="dropdown" class="dropdown-toggle  pull-right" href="#" id="user-options">
                     <div class="iconset top-settings"></div>
                  </a>
                  <ul class="dropdown-menu  pull-right" role="menu" aria-labelledby="user-options">
                     <li>
                        <a href="<?php echo ($this->config->item('saml_sp_active') ? base_url('saml/slo') : base_url('login/keluar')); ?>"><i class="fa fa-power-off ico-space"></i>Log Out</a>
                     </li>
                  </ul>
               </li>
            </ul>
         </div>
      </div>

      <!-- END RESPONSIVE MENU TOGGLER -->
      <div class="header-quick-nav">
         <!-- BEGIN TOP NAVIGATION MENU -->
         <div class="pull-left">
            <ul class="nav quick-section">
               <li class="quicklinks">
                  <a href="#" class="" id="layout-condensed-toggle">
                     <div class="iconset top-menu-toggle-dark"></div>
                  </a>
               </li>
            </ul>

         </div>
         <style>
            ul li {
               list-style: none;
               margin-left: 0px;
               margin-bottom: 0px;
            }

            .dropdown-menu li {

               padding-left: 0px;
               list-style-type: none;
               margin: 0px
            }
         </style>
         <!-- END TOP NAVIGATION MENU -->
         <!-- BEGIN CHAT TOGGLER -->
         <div class="pull-right" style="margin-top : 2px;">
            <!--                  <div class="chat-toggler">
                     <div class="profile-pic">
                        <img src="assets/img/profiles/avatar_small.jpg" alt="" data-src="assets/img/profiles/avatar_small.jpg" data-src-retina="assets/img/profiles/avatar_small2x.jpg" width="35" height="35" />
                     </div>
                  </div>-->
            <ul class="nav quick-section " style="margin:6px 0px 0px 0px;">
               <li class="quicklinks">
                  <div style="margin : 3px">
                     <i class="i i-study"></i>
                     <span class="" style="display: inline-block;">

                        <span class="">
                           <?php
                           if ($this->session->userdata('user')['periode_text']) {
                              $d_periode = "<select id='periode_header' onchange='periode_header_change()'>";
                              foreach ($list_periode as $key => $value) {
                                 $d_periode .= '<option value="' . $key . '" ';
                                 if ($key == $aktif['kode_periode']) {
                                    $d_periode .= 'selected="selected"';
                                 }
                                 $d_periode .= '>' . $value . '</option>';
                              }
                              $d_periode .= "</select>";
                              echo $d_periode;
                              // echo form_dropdown('periode_header', $list_periode, 'default');
                              // echo  $this->session->userdata('user')['periode_text'];
                           }
                           ?>
                        </span>
                        <span class="">
                           <b>|</b>

                           <?php
                           if ($this->session->userdata('user')['jenjang_text']) {
                              echo "Jenjang " . $this->session->userdata('user')['jenjang_text'];
                           } else {
                              echo "Jenjang belum di-atur";
                           }
                           ?>
                        </span>
                        <a class="auto" href="https://103.81.100.250/akademik_fkg/pilih/prodi/" data-placement="left" data-toggle="tooltip" title="" data-original-title="Set Fakultas">
                           <i class="i i-settings" style="margin-right:3px;">

                           </i>
                        </a>
                     </span>
                  </div>
               </li>
               <li class="quicklinks">
                  <a data-toggle="dropdown" class="dropdown-toggle  pull-right " href="#" id="user-options">
                     <div class="iconset top-settings-dark "></div>
                  </a>
                  <ul class="dropdown-menu  pull-right" role="menu" aria-labelledby="user-options">
                     <li>
                        <a href="<?php echo ($this->config->item('saml_sp_active') ? base_url('saml/slo') : base_url('login/keluar')); ?>">
                           <i class="fa fa-power-off ico-space"></i>
                           Log Out
                        </a>
                     </li>
                  </ul>
               </li>
            </ul>
         </div>
         <!-- END CHAT TOGGLER -->
      </div>
      <!-- END TOP NAVIGATION MENU -->
   </div>
   <!-- END TOP NAVIGATION BAR -->
</div>
<!-- END HEADER -->


<?php
//$role = $this->session->user['role'];
//if($role == "superadmin")
$this->load->view('template/sidebar', $aktif);
?>