<?php
//untuk mengetahui bulan bulan
if ( ! function_exists('bulan'))
{
    function bulan($bln)
    {
        switch ($bln)
        {
            case 1:
                return "Januari";
                break;
            case 2:
                return "Februari";
                break;
            case 3:
                return "Maret";
                break;
            case 4:
                return "April";
                break;
            case 5:
                return "Mei";
                break;
            case 6:
                return "Juni";
                break;
            case 7:
                return "Juli";
                break;
            case 8:
                return "Agustus";
                break;
            case 9:
                return "September";
                break;
            case 10:
                return "Oktober";
                break;
            case 11:
                return "November";
                break;
            case 12:
                return "Desember";
                break;
        }
    }
}
 
//format tanggal yyyy-mm-dd
if ( ! function_exists('tgl_indo'))
{
    function tgl_indo($tgl)
    {
        $ubah = gmdate($tgl, time()+60*60*8);
        $pecah = explode(" ",$ubah);
        $pecah = explode("-",$pecah[0]);//memecah variabel berdasarkan -
        $tanggal = $pecah[2];
        $bulan = bulan($pecah[1]);
        $tahun = $pecah[0];
        return $tanggal.' '.$bulan.' '.$tahun; //hasil akhir
    }
}
 
//format tanggal timestamp
if( ! function_exists('tgl_indo_timestamp')){
 
function tgl_indo_timestamp($tgl)
{
    $inttime=date('Y-m-d H:i:s',$tgl); //mengubah format menjadi tanggal biasa
    $tglBaru=explode(" ",$inttime); //memecah berdasarkan spaasi
     
    $tglBaru1=$tglBaru[0]; //mendapatkan variabel format yyyy-mm-dd
    $tglBaru2=$tglBaru[1]; //mendapatkan fotmat hh:ii:ss
    $tglBarua=explode("-",$tglBaru1); //lalu memecah variabel berdasarkan -
 
    $tgl=$tglBarua[2];
    $bln=$tglBarua[1];
    $thn=$tglBarua[0];
 
    $bln=bulan($bln); //mengganti bulan angka menjadi text dari fungsi bulan
    $ubahTanggal="$tgl $bln $thn | $tglBaru2 "; //hasil akhir tanggal
 
    return $ubahTanggal;
}
}

//format tanggal timestamp
if( ! function_exists('tgl_list')){
 	function tgl_list()
	{
		$tgl=array();
		for ($a=1;$a<32;$a++){
			if ($a<10){
				$idx="0".$a;
				$tgl[$idx]=$idx;
			} else
			$tgl[$a]=$a;
				
		}
		return $tgl;
	}
}

if( ! function_exists('bulan_list')){
 	function bulan_list()
	{
		$bln=array(
			'01'=>'Januari',
			'02'=>'Februari',
			'03'=>'Maret',
			'04'=>'April',
			'05'=>'Mei',
			'06'=>'Juni',
			'07'=>'Juli',
			'08'=>'Agustus',
			'09'=>'September',
			'10'=>'Oktober',
			'11'=>'November',
			'12'=>'Desember'
		);
		
		return $bln;
	}
}

if( ! function_exists('tahun_list')){
 	function tahun_list()
	{
		$thn=array();
		for ($a=date('Y');$a>=1950;$a--){
			$thn[$a]=$a;
				
		}
		return $thn;
	}
}

if( ! function_exists('tahun_lulus')){
 	function tahun_lulus()
	{
		$thn=array();
		for ($a=date('Y');$a>=1990;$a--){
			$thn[$a]=$a;
				
		}
		return $thn;
	}
}

if( ! function_exists('kelompok_ujian')){
 	function kelompok_ujian()
	{
		$bln=array(
		
			'IPA'=>'IPA',
			'IPS'=>'IPS',
			'IPC'=>'IPC'
		);
		
		return $bln;
	}
}
if( ! function_exists('add_nol')){
function add_nol($angka)
	{
		if ($angka<10) return "000".$angka; else
		if ($angka<100) return "00".$angka; else
		if ($angka<1000) return "0".$angka;
		return $angka;
	
	}
}

//mengetahui apakah format tanggalnya Y-m-d H:i:s
//jika benar return TRUE
if( ! function_exists('cekFormatTanggal'))
{
    function cekFormatTanggal($tgl, $format = 'd-m-Y H:i:s')
    {
        $d = DateTime::createFromFormat($format, $tgl);
        //return $d;
        return $d && $d->format($format) == $tgl;
    }
}
