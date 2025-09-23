<?php
    echo "<br>El usuario ";
    echo $_POST['nombre'] .' '. $_POST['apellidos'];
    echo '<br>con el correo electrónico: ';
    echo $_POST['email'];
    echo '<br>ha escrito el siguiente mensaje: ';
    echo $_POST['comentario'];
    echo '<br>a fecha: ';
    echo $_POST['fecha'];
?>