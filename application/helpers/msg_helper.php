<?php 
	if ( ! function_exists('msg'))
	{
		function msg($status,$msg)
		{
			$pesan='<div class="alert alert-'.($status?"success":"alert").'">
                                <button type="button" class="close" data-dismiss="alert"></button>
				<i class="fa fa-ok-sign"></i>
                                 '.$msg.'
                           </div>';
                        return $pesan;
		}
	}