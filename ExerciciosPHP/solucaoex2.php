<?php
$valor = $_POST['valor'];

$v10 = $valor / 10;


if (is_int($v10)) {

 ?> <b> <p> <?php echo 'É divicivel por 10';
} else {
  ?> <b> <p> <?php  echo 'Não dificivel por 10';
}


$v5 = $valor / 5 ;


if (is_int($v5)) {

  ?> <b> <p> <?php  echo 'É divicivel por 5';
} else {
  ?> <b> <p> <?php echo 'Não dificivel por 5';
}



$v2 = $valor / 2 ;


if (is_int($v2)) {

  ?> <b> <p> <?php  echo 'É divicivel por 2';
} else {
  ?> <b> <p> <?php echo 'Não dificivel por 2';
}


?> <br><?php
//OUTRA FORMA DE USAR O IF, TUDO JUNTO


if((is_int($v10)) or (is_int($v5)) or (is_int($v2))){

  ?> <b> <p> <?php  echo 'É divicivel por 10 ou 5 ou 2';
} else {
  ?> <b> <p> <?php echo 'Não é dificivel';
}

?>