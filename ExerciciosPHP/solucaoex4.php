<?php  

$x = $_POST['qtd'];

?>
<br> <p>
<?php echo 'Vamos repetir ' . $x .'';

$i= 1;
for ($i = 1; ; $i++) {
    if ($i > $x) {
        break;
    }
    ?><br> <p><?php
    echo 'SOL';
}

?>