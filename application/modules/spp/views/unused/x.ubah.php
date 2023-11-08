<!-- BEGIN PAGE CONTAINER-->

<style>
.error_msg{color:red}
</style>
<div class="page-content">
    <!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->
    <div class="content">
        <div class="page-title col-lg-8 col-md-8 col-sm-8 col-xs-8">
            <i class="fa fa-address-book-o"></i>
            <h3><span class="semi-bold">Edit Pendaftar</span></h3>
        </div>
        <!--<div class="row-fluid">-->
            <!--<div class="span12">-->
                <div class="row">
					<?php $this->view('_form', array('model' => $model, 'new' => $new)); ?>
				</div>
				</div>
            <!--</div>-->
        <!--</div>-->
    </div>
</div>
