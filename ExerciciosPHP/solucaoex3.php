<?php
 
$nome  = $_POST['nome'];
$idade = $_POST['idade'];
$sexo = $_POST['sexo'];


if (($sexo == 2) and ($idade <= 25)){
Echo 'Seja Bem Vinda '.$nome. 'Vc foi aceita!!';
} else {
    Echo '  OPS  '.$nome. 'Vc NÃO foi aceita!!';
}