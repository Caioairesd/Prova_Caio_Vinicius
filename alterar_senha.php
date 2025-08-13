<?php
session_start();
require_once 'conexao.php';

//GARANTE QUE O USUARIO ESTEJA LOGADO

if(isset(['id_usuario'])){
    echo"<script></script>";
    exit();




}



?>