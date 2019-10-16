<?php
	//menentukan lama waktu crawling
	set_time_limit(10000);
	//menghilangkan notice - warning
	error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
	//ambil situs
	$situs = "https://www.okezone.com/tag/jalan-tol/";
	//dom document untuk situs
	$situsutama = new DOMDocument;
    //koneksi
    require_once("koneksi.php");
	
	for($i = 0; $i <= 610; $i+=10){
        if($i == 0){
            $situsload = $situs;
        } else {
            $situsload = $situs.$i;
        }
		$situsutama->loadHTMLFile($situsload);
		foreach ($situsutama->getElementsByTagName('a') as $link) {
			$href = $link->getAttribute('href');
			//jika href mengandung "/read/" maka diidentifikasi sebagai link berita
			if ((strpos($href, "/read")) && (strpos($href, "tol"))) {
				//dom document untuk halaman berita
				$berita = new DOMDocument;
				//load html halaman berita ke $berita
				$berita->loadHTMLFile($href);
				//explode href
				$hr = explode('/',$href);
				$id_berita = $hr[count($hr)-2];
				$id_kategori = $hr[count($hr)-3];
				$id = $id_kategori."_".$id_berita;
				$tanggal = $hr[count($hr)-4];
				$bulan = $hr[count($hr)-5];
				$tahun = $hr[count($hr)-6];
				$tgl = $tahun."-".$bulan."-".$tanggal;
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
					$query = mysqli_query($conn,"insert into okezone (id,judul,isi,created_at) VALUES ('$id','$title','$p','$tgl')");
				}
			}
		}
	}
?>