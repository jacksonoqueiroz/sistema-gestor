<?php

session_start();
ob_start();

include_once ('conexao/conexao.php');

$id_op = filter_input(INPUT_GET, "id_op", FILTER_SANITIZE_NUMBER_INT);
	

if (empty($id_op)) {
	$_SESSION['msg'] = "<div class='alert alert-danger' id='msg-success' role='alert'><i class='fa fa-circle-x' style='font-size:25px;color:red'></i><div class='alerta-erro'>Selecione uma operação!</div></div>";	
	header("Location: listar-operacao");
	exit();
}

if ((!isset($_SESSION['id'])) AND (!isset($_SESSION['nome']))) {
	$_SESSION['msg'] = '<div class="alert alert-danger" role="alert">É necessário realizar o Login, para acessar o sistema!</div>';	
	header("Location: login.php");
}

$query_op = "SELECT id, nome FROM tipo_operacao WHERE id = $id_op LIMIT 1";
$result_op = $conn->prepare($query_op);
$result_op->execute();
// $row_op = $result_op->fetch(PDO::FETCH_ASSOC);
// echo "<pre>";
// var_dump($row_op);
// echo "</pre>";

if (($result_op) AND ($result_op->rowCount() != 0)) {
	$row_op = $result_op->fetch(PDO::FETCH_ASSOC);
	$query_del_op = "DELETE FROM tipo_operacao WHERE id = $id_op";
	$excluir_op = $conn->prepare($query_del_op);
	if ($excluir_op->execute()) {

		//Mudar conforme o cadastro ou alteração
		$historico = "Operação Excluída!";
		$dados_del = " -- ";
					
		//Inseri registro do usuario na tabela histórico automatico com data usuário e ação
		$query_hist = "INSERT INTO historico (acao, nome, descricao, usuario) VALUES (:acao, :nome, :descricao, :usuario)";
		$cad_hist = $conn->prepare($query_hist);
		$cad_hist->bindParam(':usuario', $_SESSION['nome'], PDO::PARAM_STR);
		$cad_hist->bindParam(':acao', $historico, PDO::PARAM_STR);
		$cad_hist->bindParam(':descricao', $dados_del, PDO::PARAM_STR);
		$cad_hist->bindParam(':nome', $row_op['nome'], PDO::PARAM_STR);
		$cad_hist->execute();
		
		header("Location: listar-operacao");
		$_SESSION['msg'] = "<div id='msg-success' role='alert'><i class='fa fa-times-circle' style='font-size:25px;color:red'></i><div class='alerta-excluir'>Registro Excluído!</div></div>";
		
		
	}else {
		$_SESSION['msg'] = "<div class='alert alert-danger' id='msg-success' role='alert'><i class='fa fa-circle-x' style='font-size:25px;color:red'></i><div class='alerta-erro'>Registro NÃO foi excluído!</div></div>";	
	header("Location: listar-operacao");
	}

}
else{
	$_SESSION['msg'] = "<div class='alert alert-danger' id='msg-success' role='alert'><i class='fa fa-circle-x' style='font-size:25px;color:red'></i><div class='alerta-erro'>Selecione uma operação!</div></div>";	
	header("Location: listar-operacao");
	exit();
}


//Inclui o head
include_once './include/head.php';
?>
<title>Editar Operação</title>
</head>
<?php

//Inclui o menu
include_once './include/menu.php';
 //echo "Página cadastro.";
?>
	

<?php
//Inclui o footer
include_once './include/footer.php';
?>