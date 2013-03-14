<?php
require('/home/caravanasigabem1/public_html/wp-load.php');
ob_start();
get_header();
session_start();

$cpfj = trim($_POST['cpfj']);
if($cpfj != "") {
  $dados = $wpdb->get_row("SELECT * FROM carteira_participantes WHERE cpf = ".$cpfj);
  if($dados->cpf == $cpfj) {
    $_SESSION['cpfj'] = $cpfj;
    if(!file_exists('prints/'.$_SESSION["cpfj"].'.jpg')){
		sobrepoeImagens();
	}
    ?>
    
    <!-- inicio mostra carteira -->
    <style>
    #corpo_carteira { margin:20px 20px 10px; text-align:center; width:100%; height:auto; float:left; 	}
	#container			{ width: 920px; margin:0 auto; overflow: hidden; padding-top: 20px; padding-right: 30px; padding-left: 30px; }
    </style>
    

    <div id="container">
    <!-- SHARE THIS -->
    <script type="text/javascript">var switchTo5x=true;</script>
    <script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script>
    <script type="text/javascript">stLight.options({publisher: "d59d8c54-e555-42ec-9f52-5dbb9d00759b"}); </script>
    <div id="share">
    <span class='st_orkut_large' displaytext='Orkut'></span> 
    <a href="#" onclick="window.open('http://www.facebook.com/sharer/sharer.php?u=http%3A%2F%2Fwww.caravanasigabem.com.br%2F2011%2Fcarteirinha%2Fprints%2F<?php echo $_SESSION['cpfj']; ?>.jpg','_blank');"><img src="http://w.sharethis.com/images/facebook_32.png" alt="Facebook" title="Facebook" /></a>
    <!--
    <span class='st_facebook_large'  displaytext='Facebook'></span> 
    -->
    <span class='st_twitter_large' displaytext='Tweet'></span>
     <span class='st_plusone_large' displaytext='Google +1'></span>
     <span class='st_email_large' displaytext='Email'></span>
      <span class='st_sharethis_large' displaytext='ShareThis'></span>
</div>
</div>   
    <div id="corpo_carteira">
    <img src="prints/<?php echo $_SESSION['cpfj']; ?>.jpg"  width="604" height="388">
    </div>
    
<div align="center">
		<input type="button" style="width:240px; height:50px; margin: 20px 0 0; font-size: 16px;" onclick="self.print();" value="Imprimir Carteira">
		<input type="button" style="width:240px; height:50px; margin: 20px 0 0; font-size: 16px;" onclick="location.href='download.php?file=prints/<?php echo $_SESSION['cpfj']; ?>.jpg'" value="Fazer Download">
	</div>
    <!-- fim mostra carteira -->
    
    <?php
  }else{
    $_SESSION['msgstatus'] = "CPF ou CNPJ não cadastrado.";
    wp_redirect("index.php");
    exit;
  }
}else{
  $_SESSION['msgstatus'] = "Digite seu CPF ou CNPJ cadastrado.";
  wp_redirect("index.php");
  exit;
}
function sobrepoeImagens()
{
global $wpdb;
setlocale (LC_ALL, 'pt_BR');

$NomesPorLinha="";
$ArrayNomesPorLinha;
$NroCpf = $_SESSION['cpfj'];
//$sql0 = mysql_query("SELECT * FROM participantes WHERE cpf='$NroCpf'") or die(mysql_error());
$dados = $wpdb->get_row("SELECT * FROM carteira_participantes WHERE cpf = ".$NroCpf);
//$quantidade0 = mysql_num_rows($sql0);

if($dados){
	//$rs0     = mysql_fetch_assoc($sql0);
 	$nomeuSR0 = strtoupper($dados->nome);
//	$nomeuSR0   = $nome;//ucwords($rs0["nome"]);
	$dia0    = substr($dados->data_cadastro, 6, 2 );
	$mes0    = substr($dados->data_cadastro, 4, 2);
	$ano0    = substr($dados->data_cadastro, 0, 4);
}

//Define o local no servidor onde a imagem final da carteirinha sera gravada
//$caminhoFotoFinal = "images/print-carteira/SouCaminhoneiro.jpg";

//SALVA O NOME DA CARTEIRINHA USANDO O CPF DO USUARIO
$caminhoFotoFinal = 'prints/'.$NroCpf.'.jpg';

#__________________________________________________________
//String definida
$stringNome = $nomeuSR0;

//Gerando array com todos os nomes
$nomeQuebrado = explode(' ',$stringNome);

//Criando a variável que vai contar letra por letra
$contaLetra = 0;

//Variável que vai armazenar o total geral de letras
$contaLetraTotal = 0;

$o=0;
$x=0;
//Loop que vai olhar palavra por palavra do array
foreach($nomeQuebrado as $nq)
{
    //Validando se chegou ao número máximo de letras para quebrar a linha
	if($contaLetra > 14) {
        echo '<br>';
    	$contaLetra = 0;
        $o++;//quando cai nesta condição, quer dizer que o nome sofre uma quebra de linha. '$o' indica a nova posição da array para guardar a nova linha
        $NomesPorLinha=" "; //limpa a variavel para guardar o nome da nova linha
       }
	//Loop que vai olhar em cada palavra, letra por letra do array e incrementar o contador
	for($i=0;$i<strlen($nq);$i++) {
// 	    echo $nq[$i]; // escreve letra por letra de cada nome
        $contaLetra++;
		$contaLetraTotal++;
	}
	//espaço entre as palavras e incrementando contador
	echo ' ';
    $NomesPorLinha .= $nomeQuebrado[$x].' ';//Este é o nome que cabe em uma linha
    $ArrayNomesPorLinha[$o] = $NomesPorLinha;//Guarda em uma posicao do array
	$contaLetra++;
	$contaLetraTotal++;
	$x++;
 }

//Mostrando o total de letras da nossa frase
//echo '<br>Total de Letras: '.--$contaLetraTotal;

#_____________________________________________________________


//$nomeUsr= ucwords(utf8_decode($rs["nome"]));
//$NroCpf = $_POST["cpf_caravana"];

//Cria as imagens a partir das imagens de fundo e da foto
$dest = imagecreatefromjpeg('carteirinha-sistema.jpg');
if(file_exists('fotos/'.$NroCpf.'.jpg')){
	$src = imagecreatefromjpeg('fotos/'.$NroCpf.'.jpg');
}else{
	$src = imagecreatefromjpeg('fotos/semfoto.jpg');
}
//sobrepoem a foto ao fundo
//bool imagecopymerge ( resource $dst_im , resource $src_im , int $dst_x , int $dst_y , int $src_x , int $src_y , int $src_w , int $src_h , int $pct )
imagecopymerge($dest, $src, 21, 78, 0, 0, 210, 280, 100);

//Salva a imagem
$imagensSobrepostas = $caminhoFotoFinal;
imagejpeg($dest,$imagensSobrepostas,100);

//elimina os objetos
imagedestroy($dest);
imagedestroy($src);

$image = imagecreatefromjpeg($imagensSobrepostas);

//Define a cor do texto que aparecerá na imagem
$textColor = imagecolorallocate($image, 0, 63, 136);

//
$ultimaLinha;
$letra          = 16;//valor em pixels(aproximadamente)
$inicioTela     = 235;
$tamanhoTela    = 24;//tamanho do espaçõ destinado ao texto(em Qde de letras)
$tamanhoTelaPix = 24*letras;//tamanho do espaçõ destinado ao texto(em pixels)aproximadamente
//COORDENADAS DOS TEXTOS. MONTADAS DE FORMA RELATIVA. VALORES PARA A PRIMEIRA LINHA
$cOOy = 215;

for($i=0;$i<strlen($ArrayNomesPorLinha);$i++)
{
//$cOOx = $inicioTela + (($tamanhoTela / 2)-(strlen($ArrayNomesPorLinha[$i]) /2) );
//echo ($tamanhoTela / 2)-(strlen($ArrayNomesPorLinha[$i]) /2).'<br>';
$cOOx=$inicioTela + ((($tamanhoTela / 2)-(strlen($ArrayNomesPorLinha[$i]) /2)) * $letra);
imagettftext($image, 20, 00, $cOOx, $cOOy, $textColor, "MyriadPro-Bold.ttf", $ArrayNomesPorLinha[$i]);
$cOOy += 30;
if($ArrayNomesPorLinha[$i] != "")//ajuste.este array tem varias posicoes vazias
  $ultimaLinha = $cOOy;//corrdenada y do ultimo nome escrito
}
imagettftext($image, 14, 0, 290, $ultimaLinha+5, $textColor, "MyriadPro-Bold.ttf", "Participante desde ".$dia0."/".$mes0."/".$ano0);
//Salva a imagem
imagejpeg($image,$caminhoFotoFinal,100);

// Liberta a memória utilizada na criação da imagem
imagedestroy($image);

}
?>
<?php
get_footer();
ob_end_flush();
?>
