<?php

session_start();

include_once ('conexao/conexao.php');

//ABRIR ESSA PÁGINA QUANDO LOGADO
if ((!isset($_SESSION['id'])) AND (!isset($_SESSION['nome']))) {
	$_SESSION['msg'] = '<div class="alert alert-danger" role="alert">É necessário realizar o Login, para acessar o sistema!</div>';	
	header("Location: login.php");
}

//Inclui o head
include_once './include/head.php';
?>
<title>Engenharia</title>

</head>
<?php



//Inclui o menu
include_once './include/menu.php';
 //echo "Página cadastro.";
?>

<style>
        body {
            background-image: url('assets/images/image-1.jpg'); /* Substitua pela URL da imagem */
            background-size: cover;
        }
        h2 {
        	color: #fff;
        	text-align: center;
        }

        .conteudo {
            background-color: rgba(0, 0, 0, 0.5); /* Transparência para contrastar com a imagem */
            padding: 20px;
            width: 300px;
    		border-radius: 10px;
    		margin-left: 530px;

        .botao {
            display: block;
            width: 200px;
            margin: 10px auto;
            padding: 15px;
            font-size: 18px;
            color: white;
            background-color: #007BFF;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }

        .botao:hover {
            background-color: #0056b3;
        }
    </style>
    <h2>Engenharia</h2>
<div class="conteudo">
	
	<a href="<?php echo URL ?>assets/arquivo/corte_dobra.pdf" class="botao" target="_blank">Corte e Dobra</a>
        <a href="assets/arquivos/arquivo-2.pdf" class="botao" target="_blank">Arquivo 2</a>
        <a href="assets/arquivos/arquivo-3.pdf" class="botao" target="_blank">Arquivo 3</a>
        <a href="assets/arquivos/arquivo-4.pdf" class="botao" target="_blank">Arquivo 4</a>
        <a href="assets/arquivos/info-santos-dumont.png" class="botao" target="_blank">Imagem</a>
        <a href="file://C:\Users\jacks\OneDrive\Documentos\Desenvolvimento\arduino\Automacao_residencia\img\icon" class="botao" target="_blank">Pasta</a>
</div>
<?php

//Inclui o footer
include_once './include/footer.php';
?>