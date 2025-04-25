<?php

//Essa Query cadastra Ações no banco tabela hisórico

include_once ('conexao/conexao.php');

if ((!isset($_SESSION['id'])) AND (!isset($_SESSION['nome']))) {
   $_SESSION['msg'] = '<div class="alert alert-danger" role="alert">É necessário realizar o Login, para acessar o sistema!</div>';  
   header("Location: login.php");
}	
//Cadastra Histórico na tabela histórico automatico com data usuário e ação
	$query_hist = "INSERT INTO historico (acao, nome, descricao, usuario) VALUES (:acao, :nome, :descricao, :usuario)";
	$cad_hist = $conn->prepare($query_hist);
	$cad_hist->bindParam(':usuario', $_SESSION['nome'], PDO::PARAM_STR);
	$cad_hist->bindParam(':acao', $historico, PDO::PARAM_STR);
	$cad_hist->bindParam(':nome', $dados['nome'], PDO::PARAM_STR);

	//Altarear apartir deste codigo na pagina aonde for entrar a ação
	// $cad_hist->bindParam(':descricao', $dados['desc_op'], PDO::PARAM_STR);
	// $cad_hist->execute();


?>