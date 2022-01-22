<?php
$valor1 = $_POST['valor1'];
$valor2 = $_POST['valor2'];

$calculo = $valor1 +$valor2;
?>
<p> <b> Exercício 1 </b></p>
<textarea name="exe1" cols="30" rows="10" readonly="readonly">Construir um algoritmo que leia 2 números e efetue a adição. Caso o valor somado seja maior que 20, este deverá ser apresentando somando-se a ele mais 8; caso o valor somado seja menor ou igual a 20, este deverá ser apresentado subtraindo-se 5</textarea> <br /> <br>
<p>
<?php
Echo ('<b> Vamos somar  '.$valor1. ' + ' .$valor2. ' o total é ' .$calculo. '');

if ($calculo >20) {

$acresenta = $calculo + 8;  
?><br><br> <p><b><?php
Echo "É maior que 20, acresentamos 8 o total ficou: " .$acresenta;

}

if ($calculo <=20)
{
    ?><br><br> <p><b><?php
    $acresenta = $calculo + 5;  
  Echo "É menor ou igual a 20, acresentamos 5 o total ficou: " .$acresenta;
  
  }

?>