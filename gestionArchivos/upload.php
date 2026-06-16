<?php
 if(isset($_FILES['archivo'])){
   $destino = '../public/img/' . basename($_FILES['archivo']['name']);
   if(move_uploaded_file($_FILES['archivo']['tmp_name'], $destino)){
     echo 'Archivo subido correctamente';
   } else {
     echo 'Error al subir archivo';
   }
 }
?>