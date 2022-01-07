<?php

//SCRIPT QUE VERIFICA EM BANCO ORACLE SE TEM TITULOS EM ABERTO, SE TIVER FAZER:
// CADASTRA EM OUTRO BANCO MYSQL OS DADOS DE CONTATO DO CLIENTE E DO TITULO EM ABERTO
// ENVIA E-MAIL COBRANDO CLIENTE 


$count=0;
require_once('../conn.php'); 
$hoje = date('d/m/Y');

$ontem =date('d/m/Y', strtotime('-1 day'));
//CARREGA COMPONENTES DO ENVIO DE EMAIL.
require_once('src/PHPMailer.php');
require_once('src/SMTP.php');
require_once('src/Exception.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
################# BLOCO PARA VALIDAR JA SE JA TEM ALGO CADASTRADO OU NÃO TENTANDO EVITAR QUE SE REPITA OS CADASTROS #################
$dataontem = explode('/', $ontem);
$dataNoFormatoParaOBranco2 = $dataontem[2].'-'.$dataontem[1].'-'.$dataontem[0];

$hojeeditada = explode('/', $hoje);
$hojeeditada1 = $hojeeditada[2].'-'.$hojeeditada[1].'-'.$hojeeditada[0];

//VARIVEL QUE CONFIRMA SE JA TEM CADASTRO DE COBRANÇA PARA DATA  OU NÃO
$controle =0;
$sql = mysqli_query($connmysql,"select count(*) as total from cobranca where datavencimento='$dataNoFormatoParaOBranco2'") or die("Erro");
while($dados=mysqli_fetch_assoc($sql))
    {
        $controle=$dados['total'].'<br>';      
   }


//CONTROLE NÃO ACHOU NENHUM REGISTRO ? CONSULTAMOS NO ORACLE!
if ($controle == 0 ) {
    $sql = "SELECT PCPREST.VALOR, PCPREST.DTVENC, PCPREST.PREST, PCPREST.DUPLIC, PCCLIENT.CODCLI, PCCLIENT.CODUSUR1, PCCLIENT.CLIENTE, PCCLIENT.EMAIL, PCCLIENT.Telcob, PCCLIENT.TELENT FROM PCPREST, PCCLIENT WHERE PCPREST.DTVENC = TO_DATE('$ontem', 'DD/MM/YYYY') and PCPREST.VPAGO is null  and PCCLIENT.CODCLI = PCPREST.CODCLI  AND PCPREST.CODCOB ='33' ORDER BY PCCLIENT.CLIENTE" ;
    $rs = oci_parse($conn,$sql); //oci_parse equivale ao mysqli_query()
    oci_execute($rs); 
     while($row = oci_fetch_assoc($rs)):
       $idcliente = $row['CODCLI'];
       $nomecliente = $row['CLIENTE'];
       $duplicata = $row['DUPLIC'];
       $valor = $row['VALOR'];
       $datvenc = $row['DTVENC'];
       $email= $row['EMAIL'];
       $tel1= $row['TELCOB'];
       $tel2= $row['TELENT'];
       $prestacao= $row['PREST'];
       $RCA= $row['CODUSUR1'];
    //CONVERT A DATA PARA MYSQL 
      $dataP = explode('/', $datvenc);
      $dataNoFormatoParaOBranco = $dataP[2].'-'.$dataP[1].'-'.$dataP[0];
    // INSERE NO MYSQL TODOS OS TITULOS DE ONTEM VENCIDOS 

        $sqlmysql = "INSERT INTO cobranca (codcliente, cliente, duplicata, valor, datavencimento, email, tel1, tel2, notificacao1, notificacao2, notificacao3, datapagamento, prestacao, rca) VALUES (  '$idcliente', '$nomecliente', '$duplicata ', '$valor', '$dataNoFormatoParaOBranco', '$email', '$tel1', '$tel2', NULL, NULL, NULL, NULL, '$prestacao', '$RCA');";
      if(mysqli_query($connmysql, $sqlmysql)){
         $count++;
      } else{
         echo "ERROR: Could not able to execute $sqlmysql. " . mysqli_error($connmysql);
   } 
   endwhile; 
} //FINAL DA CONSULTA NO ORACLE 
$contador = 0;
$sql4 = mysqli_query($connmysql,"select count(*) as total from cobranca where datavencimento='$dataNoFormatoParaOBranco2' AND notificacao1 is null limit 10") or die("Erro");
while($dados4=mysqli_fetch_assoc($sql4)){
$contador= $dados4['total'];  }

while($contador > 0)
{
    $sql4 = mysqli_query($connmysql,"select * from cobranca where datavencimento='$dataNoFormatoParaOBranco2' AND notificacao1 is null limit 10") or die("Erro");
    while($dados4=mysqli_fetch_assoc($sql4))
        {
            $codigoweb= $dados4['codigoweb'];
            $idclientem = $dados4['codcliente'];
            $nomeclientem = $dados4['cliente'];
            $duplicatam = $dados4['duplicata'];
            $valorm = $dados4['valor'];
            $datvencm = date("d/m/Y", strtotime($dados4['datavencimento']));
            $emailm= $dados4['email'];
            $tel1m= $dados4['tel1'];
            $tel2m= $dados4['tel2'];
            echo "<tr>";
            echo "<td>".$nomeclientem."</td>";
            echo "<td>".$duplicatam."</td>";
            echo "<td>".$valorm."</td>";
            echo "<td>".$datvencm."</td>";
            echo "<td>".$emailm."</td>";
            echo "<td>".$tel1m."</td>";
            echo "<td>".$tel2m."</td>";
            echo "</tr>";
            $variavelcontrole =100;
            $mail = new PHPMailer(true);
    
            try {
                $mail->SMTPDebug = SMTP::DEBUG_SERVER;
                $mail->isSMTP();
                $mail->SMTPSecure = false;
                $mail->SMTPAutoTLS = false;
                $mail->Host = 'smtp.hostinger.com.br';
                $mail->SMTPAuth = true;
                $mail->Username = 'validacao@bazarsideral.com.br';
                $mail->Password = '+#JfG7Nr';
                $mail->Port = 587;
             
                $mail->setFrom('validacao@bazarsideral.com.br');
                $mail->addAddress('bkalata@gmail.com');
                 
                $mail->isHTML(true);
                $mail->Subject = 'Boleto em atraso';
                $mail->Body = "<html>
    
                <head>
                <meta http-equiv=Content-Type content='text/html; charset=windows-1252'>
                <meta name=Generator content='Microsoft Word 14 (filtered)''>
                <style>
                <!--
                 /* Font Definitions */
                 @font-face
                    {font-family:Calibri;
                    panose-1:2 15 5 2 2 2 4 3 2 4;}
                 /* Style Definitions */
                 p.MsoNormal, li.MsoNormal, div.MsoNormal
                    {margin:0cm;
                    margin-bottom:.0001pt;
                    font-size:12.0pt;
                    font-family:'Times New Roman','serif';}
                a:link, span.MsoHyperlink
                    {color:blue;
                    text-decoration:underline;}
                a:visited, span.MsoHyperlinkFollowed
                    {color:purple;
                    text-decoration:underline;}
                .MsoPapDefault
                    {margin-bottom:10.0pt;
                    line-height:115%;}
                @page WordSection1
                    {size:595.3pt 841.9pt;
                    margin:70.85pt 3.0cm 70.85pt 3.0cm;}
                div.WordSection1
                    {page:WordSection1;}
                -->
                </style>
                
                </head>
                
                <body lang=PT-BR link=blue vlink=purple>
                
                <div class=WordSection1>
                
                <table class=MsoNormalTable border=0 cellspacing=0 cellpadding=0 width='100%'
                 style='width:100.0%;background:white;border-collapse:collapse'>
                 <tr>
                  <td valign=top style='padding:7.5pt 7.5pt 7.5pt 7.5pt'>
                  <p class=MsoNormal align=center style='text-align:center'><span
                  style='font-size:30.0pt;color:#ED2530'><br>
                  ATENÇÃO!</span></p>
                  </td>
                 </tr>
                </table>
                
                <p class=MsoNormal>&nbsp;</p>
                
                <table class=MsoNormalTable border=0 cellspacing=0 cellpadding=0 width='100%'
                 style='width:100.0%;background:white;border-collapse:collapse;background-position-x:0%;
                 background-position-y:0%;background-attachment:scroll'>
                 <tr style='height:1.5pt'>
                  <td valign=top style='padding:7.5pt 7.5pt 7.5pt 7.5pt;height:1.5pt'>
                  <div align=center>
                  <table class=MsoNormalTable border=1 cellspacing=0 cellpadding=0 width='100%'
                   style='width:100.0%;border:none;border-top:solid windowtext 1.0pt;
                   border-color:currentcolor currentcolor'>
                   <tr>
                    <td style='border:none;padding:0cm 0cm 0cm 0cm'>
                    <p class=MsoNormal style='line-height:0%'><span style='color:#CCCCCC'>&nbsp;
                    </span></p>
                    </td>
                   </tr>
                  </table>
                  </div>
                  </td>
                 </tr>
                </table>
                
                <p class=MsoNormal>&nbsp;</p>
                
                <table class=MsoNormalTable border=0 cellspacing=0 cellpadding=0 width='100%'
                 style='width:100.0%;background:white;border-collapse:collapse;background-position-x:0%;
                 background-position-y:0%;background-attachment:scroll'>
                 <tr>
                  <td valign=top style='padding:7.5pt 7.5pt 7.5pt 7.5pt'>
                  <p class=MsoNormal align=center style='text-align:center;line-height:130%'><strong><span
                  style='font-size:10.5pt;line-height:130%;color:#5E5E5E'>$nomeclientem</span></strong></p>
                  <p class=MsoNormal style='line-height:130%'><span style='font-size:10.5pt;
                  line-height:130%;color:#5E5E5E'>&nbsp;</span></p>
                  <p class=MsoNormal align=center style='text-align:center;line-height:130%'><span
                  style='font-size:10.5pt;line-height:130%;color:#5E5E5E'>Lembramos que seu
                  título $duplicatam, que venceu no dia <strong>$datvencm</strong>, <br>
                  no valor de <strong>R$ $valorm</strong> encontra-se em aberto no nosso contas
                  a receber.<br>
                  &nbsp;<br>
                  &nbsp;<br>
                  &nbsp;<br>
                  Por favor contatar o setor de Crédito e Cobrança&nbsp;<br>
                  <strong>Telefone</strong>: (51) 3373 - 4523&nbsp;<br>
                  <strong>WhatsApp</strong>: (51) 99366 - 3917&nbsp;<br>
                  <strong>E-mail</strong>: <a
                  href='mailto:cobranca@himalaiadistribuidora.com.br' target='_blank'>cobranca@himalaiadistribuidora.com.br</a><br>
                  &nbsp;<br>
                  &nbsp;<br>
                  &nbsp;<br>
                  Obs.: Caso já tenha efetuado o pagamento, desconsidere este aviso.&nbsp;<br>
                  &nbsp;<br>
                  Obrigado.</span></p>
                  </td>
                 </tr>
                </table>
                
                <p class=MsoNormal>&nbsp;</p>
                
                </div>
                
                </body>
                
                </html>";
                         
                if($mail->send()) {
                    
                    $sqlmysql = "update cobranca set notificacao1='$hojeeditada1' where codigoweb='$codigoweb' ";
                    if(mysqli_query($connmysql, $sqlmysql)){
                        echo 'Email enviado com sucesso';}
    
                } else {
                    echo 'Email nao enviado';
                }
            } catch (Exception $e) {
                echo "Erro ao enviar mensagem: {$mail->ErrorInfo}";
            }
    
    
       //FINAL DO LACO DO COUNT
    }
    


$contador--;
}
echo 'E-mails enviados na data de hoje';
echo  $ontem;
?>









