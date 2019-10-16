<?php
	$servername = "localhost";
	$username 	= "root";
	$password	= "";
	$dbname		= "data_berita";

	//koneksi
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	mysqli_set_charset($conn, "utf8");

	//cek koneksi
	if(mysqli_connect_errno()){
		echo "Koneksi ke MySQL gagal! ". mysqli_connect_error();
	}
?>