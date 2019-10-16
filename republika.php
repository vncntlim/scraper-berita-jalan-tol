<?php
	//menentukan lama waktu crawling
	set_time_limit(10000);
	//menghilangkan notice - warning
	error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
	//ambil situs
	$situs = "https://www.republika.co.id/indeks/hot_topic/jalan-tol/";
	//dom document untuk situs
	$situsutama = new DOMDocument;
    //koneksi
    require_once("koneksi.php");

	for($i = 0; $i <= 400; $i+=40){
		$situsload = $situs.$i;
		$situsutama->loadHTMLFile($situsload);
		foreach ($situsutama->getElementsByTagName('a') as $link) {
			$href = $link->getAttribute('href');
			//jika href mengandung "/read/" maka diidentifikasi sebagai link berita
			if ((strpos($href, "/berita")) && (strpos($href, "tol"))) {
				//dom document untuk halaman berita
				$berita = new DOMDocument;
				//load html halaman berita ke $berita
				$berita->loadHTMLFile($href);
				//explode href
				$hr = explode('/',$href);
				$id = $hr[count($hr)-2];
				//untuk setiap tag title pada halaman berita, lakukan
				foreach ($berita->getElementsByTagName('title') as $tit) {
					//tampung hasil title
					$title = $tit->nodeValue;
					$p = '';
					foreach ($berita->getElementsByTagName('p') as $isi) {
						$par = $isi->nodeValue;
						if(strpos($par, "Baca juga")){
							
						} else {
							$p .= " ".$par;
						}
					}
					foreach ($berita->getElementsByTagName('div') as $divi){
						$cdivi = $divi->getAttribute('class');
						if($cdivi == 'date_detail'){
							$waktu = $divi->nodeValue;		
						}
					}
					$query = mysqli_query($conn,"insert into republika (id,judul,isi,created_at) VALUES ('$id','$title','$p','$waktu')");
				}
			}
		}
	}
?>