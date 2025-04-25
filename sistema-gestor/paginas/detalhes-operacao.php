<?php

session_start();
ob_start();

include_once ('conexao/conexao.php');

$id_op = filter_input(INPUT_GET, "id_op", FILTER_SANITIZE_NUMBER_INT);
// echo "<pre>";
// var_dump($id_op);
// echo "</pre>";

if (empty($id_op)) {
	$_SESSION['msg'] = "<div class='alert alert-danger' id='msg-success' role='alert'><i class='fa fa-circle-x' style='font-size:25px;color:red'></i><div class='alerta-erro'>Selecione uma operação!</div></div>";	
	header("Location: listar-operacao");
	exit();
}

if ((!isset($_SESSION['id'])) AND (!isset($_SESSION['nome']))) {
	$_SESSION['msg'] = '<div class="alert alert-danger" role="alert">É necessário realizar o Login, para acessar o sistema!</div>';	
	header("Location: login.php");
}


//Inclui o head
include_once './include/head.php';
?>
<title>Detalhes</title>
</head>
<?php

//Inclui o menu
include_once './include/menu.php';
 //echo "Página cadastro.";
			

			$query_op = "SELECT id, nome, desc_op, cod_op, usuario, created FROM tipo_operacao WHERE id = $id_op LIMIT 1";
			$result_op = $conn->prepare($query_op);
			$result_op->execute();

			if (($result_op) AND ($result_op->rowCount() != 0)) {
				$row_op = $result_op->fetch(PDO::FETCH_ASSOC);
				// echo "<pre>";
				// var_dump($row_op);
				// echo "</pre>";
				extract($row_op);
			}else {
				$_SESSION['msg'] = "<div class='alert alert-danger' id='msg-success' role='alert'><i class='fa fa-circle-x' style='font-size:25px;color:red'></i><div class='alerta-erro'>Operação não Encontrado!</div></div>";	
				header("Location: listar-operacao");
			}
		?>
	<div class="container py-4">
		<header class="pb-3 mb-4">
			<div class="row">
				<div class="col-md-4">
					<h3>Detalhes da Operação: </h3>
				</div>
				<div class="col-md-3">
					<p class="titulo-op"><?php echo $nome; ?></p>
				</div>
				
			</div>
			
			<hr>
		</header>
		<div class="row" style="margin-top: -20px;">
			<div class="col-md-3">
				<p class="registro">Código da operação: <strong><?php echo $cod_op; ?></strong> </p>
			</div>			
		</div>
		<hr>
		<div class="row">
			<div class="col-md-3">
				<p class="registro"><?php 
				$created = date('d/m/Y');
				echo $desc_op ?></p>				
			</div>
		</div>
		<hr>
		<br>
		<div class="row" style="margin-top: -20px;">
			<div class="col-md-4">
				<p class="registro"><i>Cadastrado em: <strong> <?php echo $created; ?></strong></i></p>
			</div>			
		</div>
	</div>

<?php
//Inclui o footer
include_once './include/footer.php';
?>