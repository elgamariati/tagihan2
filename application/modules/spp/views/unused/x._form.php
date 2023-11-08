<?php
 
/**
 * @var $model Prodi_m
 */
$listProdi = $this->database->listProdi();
$listJalur = $this->database->listJalur();
?>
				<?php echo form_open(current_url(), array('class' => 'col-md-12 col-sm-12 col-xs-12')); ?>
					
                   <div class="col-md-6">
					  <div class="grid simple">
						<div class="grid-title no-border">
						  <h4>Biodata <span class="semi-bold">Peminat</span></h4>
						  <div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
						</div>
						<div class="grid-body no-border">
						  <div class="row-fluid">
							<div class="span8">
							  <div class="form-group col-lg-12 col-md-12 ">
								<label class="form-label">Nomer Peserta</label>
								<span class="help"></span>
								<div class="controls">
									<?php echo form_input('regNopes', $model->regNopes, array('class' => 'form-control','placeholder' => 'Isikan nomer peserta')); ?>   
									<div class="error_msg">
										<?php echo (form_error('regNopes') != "" ? "Wajib diisi":"");?>
									</div>
								</div>
								</div>
								 <div class="form-group col-lg-12 col-md-12 ">
									<label class="form-label">Nama</label>
									<span class="help"></span>
									<div class="controls">
										<?php echo form_input('regNama', $model->regNama, array('class' => 'form-control','placeholder' => 'Isikan nama peserta')); ?>   
										<div class="error_msg">
											<?php echo (form_error('regNama') != "" ? "Wajib diisi":"");?>
										</div>
									</div>
								</div>
								<div class="form-group col-lg-6 col-md-6">
									<label class="form-label">Jalur Masuk</label>
									<span class="help"></span>
									<div class="controls">
										<?php echo form_dropdown('regJalurMasuk',$listJalur,$model->regJalurMasuk,array('class' => 'chosen-select form-select drop_select','style'=>'width:100%'));?>
										<div class="error_msg">        
											<span><?php echo form_error('regJalurMasuk');?></span>
										</div>
									</div>
								</div>
								<div class="form-group col-lg-6 col-md-6">
									<label class="form-label">Penerima Bidikmisi</label>
									<span class="help"></span>
									<div class="controls">
									  <div class="radio">
										<input id="ya" type="radio" name="regBidikmisi" value="YA" <?php if ($model->regBidikmisi=="YA") echo "checked";?>>
										<label for="ya">Ya</label>
										<input id="tidak" type="radio" name="regBidikmisi" value="TIDAK" <?php if ($model->regBidikmisi=="TIDAK") echo "checked"; else if ($model->regBidikmisi=="") echo "checked";?>>
										<label for="tidak">Tidak</label>
									  </div>
									</div>
								</div>
							</div>
						  </div>
						</div>
					  </div>
					</div>
					
                   <div class="col-md-6">
					  <div class="grid simple">
						<div class="grid-title no-border">
						  <h4>Pilihan <span class="semi-bold">Program Studi</span></h4>
						  <div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
						</div>
						<div class="grid-body no-border">
						  <div class="row-fluid">
							<div class="span8">
								
								<div class="form-group col-lg-12 col-md-12 <?php echo (form_error('regProdiPil1') != "" ? "has-error":"");?>">
									<label class="form-label">Pilihan 1</label>
									<div class="controls">
										<?php echo form_dropdown('regProdiPil1',$listProdi,$model->regProdiPil1,array('class' => 'chosen-select form-select drop_select','style'=>'width:100%'));?>
										<div class="error_msg">        
											<?php echo (form_error('regProdiPil1') != "" ? "Wajib diisi":"");?>
										</div>
									</div>
								</div>
								<div class="form-group col-lg-12 col-md-12">
									<label class="form-label">Pilihan 2</label>
									<span class="help"></span>
									<div class="controls">
										<?php echo form_dropdown('regProdiPil2',$listProdi,$model->regProdiPil2,array('class' => 'chosen-select form-select drop_select','style'=>'width:100%'));?>
										<div class="error_msg">        
											<span><?php echo form_error('regProdiPil2');?></span>
										</div>
									</div>
								</div>
								<div class="form-group col-lg-12 col-md-12">
									<label class="form-label">Pilihan 3</label>
									<span class="help"></span>
									<div class="controls">
										<?php echo form_dropdown('regProdiPil3',$listProdi,$model->regProdiPil3,array('class' => 'chosen-select form-select drop_select','style'=>'width:100%'));?>
										<div class="error_msg">        
											<span><?php echo form_error('regProdiPil3');?></span>
										</div>
									</div>
								</div>
								
								
							</div>
						  </div>
						</div>
					  </div>
					  
					</div>
					<div  class="col-md-12 col-md-offset-5">
						<center>
							<?php  echo form_submit('Submit', ($new ? " Tambah" : "Ubah"),array('class'=>'btn btn-primary  col-xs-12 col-md-2 col-lg-2','style'=>'')); ?>
						</center>
					</div>
                </form>