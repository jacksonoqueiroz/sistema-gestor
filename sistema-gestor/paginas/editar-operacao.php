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

$query_op = "SELECT id, nome, desc_op, cod_op FROM tipo_operacao WHERE id = $id_op LIMIT 1";
$result_op = $conn->prepare($query_op);
$result_op->execute();

if (($result_op) AND ($result_op->rowCount() != 0)) {
	$row_op = $result_op->fetch(PDO::FETCH_ASSOC);
	// echo "<pre>";
	// var_dump($row_op);
	// echo "</pre>";

}else{
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
	<div class="container py-4">
		<header class="pb-3 mb-4 border-bottom">
			<div class="row">
				<div class="col-md-4">
					<h3>Editar Operação</h3>
					
					<?php

					if (isset($_SESSION['msg'])) {
			        echo $_SESSION['msg'];
			        unset($_SESSION['msg']);
	    			}

					//Receber os dados do formulario
					$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
					
					//Verificar se o usuário clicou no botão
					if (!empty($dados['edit-op'])) {
						$empty_input = false;
						$dados = array_map('trim', $dados);
						if (in_array("", $dados)) {
							$empty_input = true;
							$_SESSION['msg'] = "<div class='alert alert-danger' id='msg-success' role='alert'><i class='fa fa-circle-x' style='font-size:25px;color:red'></i><div class='alerta-erro'>Preencha todos os campos!</div></div>";
						}
						if (!$empty_input) {
							 $query_up_op = "UPDATE tipo_operacao SET nome=:nome, desc_op=:desc_op, cod_op=:cod_op, modifield = NOW() WHERE id=:id";
							 $edit_op = $conn->prepare($query_up_op);
							 $edit_op->bindParam(':nome', $dados['nome'], PDO::PARAM_STR);
							 $edit_op->bindParam(':desc_op', $dados['desc_op'], PDO::PARAM_STR);
							 $edit_op->bindParam(':cod_op', $dados['cod_op'], PDO::PARAM_STR);
							 $edit_op->bindParam(':id', $id_op, PDO::PARAM_INT);
							 if ($edit_op->execute()) {
							 		$_SESSION['msg'] = "<div id='msg-success' role='alert'><i class='fa fa-check-circle' style='font-size:25px;color:green'></i><div class='alerta-sucesso'>Registro foi Editado!</div></div>";
							 		//Mudar conforme o cadastro ou alteração
									$historico = "Operação Editado.";
					
									//Inclui o Query historico
									include_once './include/historico.php';

									//Continuar salvar ação no historico do sistema MUDAR A CADA ALTERAÇÃO DA TABELA
									$cad_hist->bindParam(':descricao', $dados['desc_op'], PDO::PARAM_STR);
									$cad_hist->execute();

									header("Location: listar-operacao");
							 }else{
							 		$_SESSION['msg'] = "<div class='alert alert-danger' id='msg-success' role='alert'><i class='fa fa-circle-x' style='font-size:25px;color:red'></i><div class='alerta-erro'>Registro não foi Editado!</div></div>";
							 }
						}
					}

					?>
				
				</div>
			</div>
		</header>
		<form id="edit-op" name="cad_op" class="form-group" method="POST" action="">
		<div class="row">
			<div class="col-md-3">
		    	<label for="inputEmail4" class="form-label">Nome da Operação</label>
		    	<input type="text" name="nome" autocomplete="off" class="form-control" id="nome" reset() value="<?php 
		    		if (isset($dados['nome'])) {
		    			echo $dados['nome'];
		    		}elseif (isset($row_op['nome'])) {
		    			echo $row_op['nome'];
		    		}
		    	?>">	
		  </div>
		  <div class="col-md-6">
		    <label for="inputText" class="form-label">Descrição</label>
		    <input type="text" name="desc_op" autocomplete="off" class="form-control" id="desc_op" value=" <?php 
		    	if (isset($dados['desc_op'])) {
		    			echo $dados['desc_op'];
		    		}elseif (isset($row_op['desc_op'])) {
		    		echo $row_op['desc_op'];
		    	}
		     ?>">
	  </div>
	  <div class="col-md-3">
		    <label for="inputPassword4" class="form-label">Código da Operação</label>
		    <input type="text" name="cod_op" autocomplete="off" class="form-control" id="cod_op" value=" <?php 
		    	if (isset($dados['cod_op'])) {
		    			echo $dados['cod_op'];
		    		}elseif (isset($row_op['cod_op'])) {
		    		echo $row_op['cod_op'];
		    	}
		     ?>">
	  </div>	  
	  	<div class="col-3 but-op">
	    	<input type="submit" value="Editar" name="edit-op" class="btn btn-success">
	  	</div>
	  </div>
	</form>
		
	</div>

<?php
//Inclui o footer
include_once './include/footer.php';
?>