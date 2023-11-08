<!-- BEGIN PAGE CONTAINER-->
<div class="page-content">
    <!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->
    <div class="content">
        <div class="page-title col-lg-8 col-md-8 col-sm-8 col-xs-8">
            <i class="fa fa-address-book-o"></i>
            <h3><span class="semi-bold">Ubah Password</span></h3>
        </div>
        <!--<div class="row-fluid">-->
            <!--<div class="span12">-->
                <div class="row">
                    <div class="col-md-12">
                        <div class="grid simple">
                            <style>
                                .col-lg-12,
                                .col-lg-6
                                {
                                    margin-right: 6px !important;
                                }
                            </style>
                            <div class="grid-body no-border">
                                <br>
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
                                        <?php if($this->session->has_userdata("msg")):?>
                                            <div class="alert alert-success">
                                                <em><?php echo $this->session->msg;?></em>
                                            </div>
                                        <?php endif; ?>
                                        <?php echo form_open(current_url(), array('class' => 'form-horizontal')); ?>
                                        <div class="form-group col-lg-12 col-md-6">
                                            <label class="form-label">Password Lama</label>
                                            <div class="controls">
                                                <?php echo form_password('password_old','',array("id"=>"password",'class'=>'form-control')); ?>
                                                <div class="error_msg">        
                                                    <span><?php echo form_error('password_old');?></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group col-lg-12 col-md-6">
                                            <label class="form-label">Password Baru</label>
                                            <div class="controls">
                                                <?php echo form_password('password','',array("id"=>"password",'class'=>'form-control')); ?>
                                                <div class="error_msg">        
                                                    <span><?php echo form_error('password');?></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group col-lg-12 col-md-6">
                                            <label class="form-label">Ulangi Password Baru</label>
                                            <div class="controls">
                                                <?php echo form_password('ulangPassword','',array("id"=>"ulangPassword",'class'=>'form-control')); ?>
                                                <div class="error_msg">        
                                                    <span><?php echo form_error('ulangPassword');?></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> 
                            <div class="grid-body no-border">
                                <br>
                                <div class="row">
                                    <div class="col-md-6 col-sm-12 col-xs-12">
                                        <div class="form-group col-lg-6 col-md-6" style="float : left  !important">
                                            <?php  echo form_submit('Submit', ("Ubah"),array('class'=>'btn btn-primary btn-cons','style'=>'margin-left: -15px;')); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php echo form_close(); ?>
                        </div>
                    </div>
                </div>
            <!--</div>-->
        <!--</div>-->
    </div>
</div>
