<?php
 $conn = new mysqli('localhost', 'root', '', 'ofertapp');
 if($conn->connect_error){ die('Error de conexión: ' . $conn->connect_error); }
?>