<?php 
	if ( ! function_exists('rupiah'))
	{
		function rupiah($angka)
		{
			if($angka==null)
			{
				return "Kosong";
			}
			else
			{
				$jumlah_desimal="0";
				$pemisah_desimal=",";
				$pemisah_ribuan=".";
				return  $rupiah=number_format($angka,$jumlah_desimal,$pemisah_desimal,$pemisah_ribuan);
			}
		}
	}