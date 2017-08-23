<?php 
	define(DS, DIRECTORY_SEPARATOR);
	require_once "phpqrcode".DS."qrlib.php";


	//Para nombres ascendentes
	function agregarQR($objId,$cantidad){
		if($cantidad>0){
			//se obtiene el directorio de las imagenes
			$directorio = "..".DS."pictures".DS."$objId".DS;
			//se genera el código QR y se guarda para todas las fotos.
			$qrName=$directorio."qr_".$objId.".png";
			QRcode::png("http://monitoreoagua.ucr.ac.cr/android/DerechosAutor/author.php?objid=$objId", $qrName,QR_ECLEVEL_H, 2, 2);  
			
			//se agrega el QR a cada una de las fotos
			for($i=0;$i<$cantidad;$i++){
				$imgName=$directorio.$i.".jpg";//nombre con el que se guardó la imagen sin QR
				$stamp = imagecreatefrompng($qrName);//se obtiene el QR creado anteriormente
				$im = imagecreatefromjpeg($imgName);//se obtiene la imagen
			 	
				// Set the margins for the stamp and get the height/width of the stamp image
				$imx = imagesx($im);
				$imy =imagesy($im);
				$sx = imagesx($stamp);
				$sy =imagesy($stamp);
				$position = ubicacion(1,$imx,$imy,$sx,$sy);//función para obtener donde se desea colocar el QR.
				// Copy the stamp image onto our photo using the margin offsets and the photo 
				// width to calculate positioning of the stamp. 
				imagecopy($im, $stamp,$position[0],$position[1], 0, 0, $sx, $sy);
				$newImgName=$directorio."$i.jpg";
				imagejpeg($im,$newImgName,75);//se guarda la imagen en la carpeta destino.
				//free memory and destroy
				//unlink($imgName);
				imagedestroy($im);
				imagedestroy($stamp);
			}
			unlink($qrName);
		}

	}


	function agregarQRNuevas($objId,$cantidad,$nombres){
		if($cantidad>0){
			//se obtiene el directorio de las imagenes
			$directorio = "..".DS."pictures".DS."$objId".DS;
			//se genera el código QR y se guarda para todas las fotos.
			$qrName=$directorio."qr_".$objId.".png";
			QRcode::png("http://monitoreoagua.ucr.ac.cr/android/DerechosAutor/author.php?objid=$objId", $qrName,QR_ECLEVEL_L, 2, 2);  
			
			//se agrega el QR a cada una de las fotos
			for($i=0;$i<$cantidad;$i++){
				$imgName=$directorio.$nombres[$i];//nombre con el que se guardó la imagen sin QR
				$stamp = imagecreatefrompng($qrName);//se obtiene el QR creado anteriormente
				$im = imagecreatefromjpeg($imgName);//se obtiene la imagen
			 	
				// Set the margins for the stamp and get the height/width of the stamp image
				$imx = imagesx($im);
				$imy =imagesy($im);
				$sx = imagesx($stamp);
				$sy =imagesy($stamp);
				$position = ubicacion(1,$imx,$imy,$sx,$sy);//función para obtener donde se desea colocar el QR.
				// Copy the stamp image onto our photo using the margin offsets and the photo 
				// width to calculate positioning of the stamp. 
				imagecopy($im, $stamp,$position[0],$position[1], 0, 0, $sx, $sy);
				$newImgName=$directorio.$nombres[$i];
				imagejpeg($im,$newImgName,75);//se guarda la imagen en la carpeta destino.
				//free memory and destroy
				//unlink($imgName);
				imagedestroy($im);
				imagedestroy($stamp);
			}
			unlink($qrName);
		}

	}
	function ubicacion($position,$imx,$imy,$sx,$sy)
	{//(0=izq:arriba),(1=der:arriba),(2=der:abajo),(3=izq:abajo) 
		$resultado= array();
		switch ($position) {
			case 0:
				$resultado[0]=1+5;
				$resultado[1]=1+5;
				break;
			case 1:
				$resultado[0]=$imx-$sx-5;
				$resultado[1]=1+5;
				break;
			case 2:
				$resultado[0]=$imx-$sx-5;
				$resultado[1]=$imy-$sy-5;
				break;
			case 3:
				$resultado[0]=1+5;
				$resultado[1]=$imy-$sy-5;
				break;
			default:
				break;
		}
		return $resultado;
	}
 ?>