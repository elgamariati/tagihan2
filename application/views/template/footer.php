<?php

?>
<script src="<?php echo base_url(); ?>assets/plugins/boostrapv3/js/bootstrap.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/plugins/breakpoints.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/plugins/jquery-unveil/jquery.unveil.min.js" type="text/javascript"></script>
<!-- END CORE JS FRAMEWORK -->
<script src="<?php echo base_url(); ?>assets/plugins/jquery-scrollbar/jquery.scrollbar.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/plugins/jquery-numberAnimate/jquery.animateNumbers.js" type="text/javascript"></script>
<!-- END CORE PLUGINS -->
<!-- Swal -->
<script src="<?php echo base_url(); ?>assets/js/swal.min.js" type="text/javascript"></script>
<!-- End of Swal -->
<!-- Toastr -->
<script src="<?php echo base_url(); ?>assets/plugins/toastr/toastr.min.js" type="text/javascript"></script>
<!-- End of Toastr -->

<!-- BEGIN PAGE LEVEL PLUGINS -->
<script src="<?php echo base_url(); ?>assets/plugins/chosen/docsupport/prism.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/plugins/chosen/docsupport/init.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/plugins/chosen/chosen.jquery.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/plugins/pace/pace.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/plugins/jquery-block-ui/jqueryblockui.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/plugins/jquery-inputmask/jquery.inputmask.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/plugins/jquery-autonumeric/autoNumeric.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/plugins/ios-switch/ios7-switch.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/plugins/bootstrap-select2/select2.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/plugins/bootstrap-wysihtml5/wysihtml5-0.3.0.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/plugins/bootstrap-tag/bootstrap-tagsinput.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/plugins/boostrap-clockpicker/bootstrap-clockpicker.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/plugins/dropzone/dropzone.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/plugins/jquery-scrollbar/jquery.scrollbar.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/plugins/jquery-block-ui/jqueryblockui.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/plugins/jquery-numberAnimate/jquery.animateNumbers.js" type="text/javascript"></script>
<!--      <script src="<?php echo base_url(); ?>assets/plugins/jquery-datatable/js/jquery.dataTables.min.js" type="text/javascript"></script>
      <script src="<?php echo base_url(); ?>assets/plugins/jquery-datatable/extra/js/dataTables.tableTools.min.js" type="text/javascript"></script>
      <script type="text/javascript" src="<?php echo base_url(); ?>assets/plugins/datatables-responsive/js/datatables.responsive.js"></script>
      <script type="text/javascript" src="<?php echo base_url(); ?>assets/plugins/datatables-responsive/js/lodash.min.js"></script>-->
<!-- END PAGE LEVEL PLUGINS -->
<script src="<?php echo base_url(); ?>assets/js/form_elements.js" type="text/javascript"></script>
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="<?php echo base_url(); ?>assets/js/core.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/js/chat.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/js/demo.js" type="text/javascript"></script>
<!-- END PAGE LEVEL SCRIPTS -->
<script>
      function periode_header_change() {
            $.ajax({
                  url: "<?php echo base_url('periode/ph_ganti'); ?>",
                  data: {
                        'kode_periode': $('#periode_header :selected').val()
                  },
                  type: "POST",
                  dataType: 'JSON',
                  beforeSend: function() {
                        toastr.warning('Mohon menunggu sedang mengubah periode!')
                  },
                  success: function(data) {
                        toastr.success('Berhasil mengubah periode!')
                        location.reload();
                  },
                  error: function(xhr, ajaxOptions, thrownError) {
                        toastr.error(xhr.status, thrownError);
                  }
            });
      }
</script>
<!-- END JAVASCRIPTS -->
</body>

</html>