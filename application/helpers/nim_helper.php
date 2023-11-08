<?php 
	if ( ! function_exists('nim'))
	{
		function nim($nim_asli)
		{
            $ref=array(
                "A"=>"01",
                "B"=>"02",
                "C"=>"03",
                "D"=>"04",
                "E"=>"05",
                "F"=>"06",
                "G"=>"07",
                "H"=>"08",
                "I"=>"09",
                "J"=>"10",
                "K"=>"11"
            );
            if(strlen($nim_asli)==9)
            {
                $nim_convert="";
                $chars = str_split($nim_asli);
                foreach($chars as $char=>$val)
                {
                    $val= strtoupper($val);
                    if (array_key_exists($val, $ref))
                        $nim_convert.=$ref[$val];
                    else 
                        $nim_convert.=$val;
                }
                return $nim_convert;
            } 
            else
                return $nim_asli;			
		}
	}