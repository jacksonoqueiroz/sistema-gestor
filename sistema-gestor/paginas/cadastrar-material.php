<?php
ob_start();
session_start();

include_once ('conexao/conexao.php');

if ((!isset($_SESSION['id'])) AND (!isset($_SESSION['nome']))) {
	$_SESSION['msg'] = '<div class="alert alert-danger" role="alert">É necessário realizar o Login, para acessar o sistema!</div>';	
	header("Location: login.php");
}
//Inclui o head
include_once './include/head.php';
?>
<title>Cadastrar Material</title>
</head>
<?php

//Inclui o menu
include_once './include/menu.php';
 //echo "Página cadastro.";

?>
<div class="container py-4 cadastro">
	<header class="pb-3 mb-4 border-bottom">
	
		<div class="row">
			<div class="col-md-4">
				
			<h2>Cadastrar Material</h2>
			</div>
			<div class="col-md-3 list-icon">
				<a href="<?php echo URL ?>listar-material"><i class="fa fa-list"></i></a>
			</div>
			
		</div>
		
	
		<?php
		if (isset($_SESSION['msg'])) {
        echo $_SESSION['msg'];
        unset($_SESSION['msg']);
    	}
		$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
		
		
		if (!empty($dados['cad_reg'])) {
			// echo "<pre>";
			// var_dump($dados);
			// echo "</pre>";
			$empty_input = false;

			 $dados = array_map('trim', $dados);
			 if (in_array("", $dados)) {
			 	$empty_input = true;
			 	echo $_SESSION['msg'] = "<div class='alert alert-danger' id='msg-success' role='alert'><i class='fa fa-circle-x' style='font-size:25px;color:red'></i><div class='alerta-erro'>Preencha todos os campos!</div></div>";
			 }
			if(!$empty_input) {
			

				$query_cad = "INSERT INTO material (nome, descricao, usuario) VALUES (:nome, :descricao,  :usuario)";
				$cad_reg = $conn->prepare($query_cad);
				$cad_reg->bindParam(':nome', $dados['nome'], PDO::PARAM_STR);
				$cad_reg->bindParam(':descricao', $dados['descricao'], PDO::PARAM_STR);
				$cad_reg->bindParam(':usuario', $_SESSION['nome'], PDO::PARAM_STR);
				$cad_reg->execute();
			if ($cad_reg->rowCount()) {
				
				$_SESSION['msg'] = "<div id='msg-success' role='alert'><i class='fa fa-check-circle' style='font-size:25px;color:green'></i><div class='alerta-sucesso'>Registro Cadastrado!</div></div>";

				//Mudar conforme o cadastro ou alteração
				$historico = "Cadastrou Material.";
				
				//Inclui o Query historico
				include_once './include/historico.php';

				//Continuar salvar ação no historico do sistema MUDAR A CADA ALTERAÇÃO DA TABELA
				$cad_hist->bindParam(':descricao', $dados['descricao'], PDO::PARAM_STR);
				$cad_hist->execute();
				unset($dados);

				header("Location: listar-material");
			}else{
				echo $_SESSION['msg'] = "<div class='alert alert-danger' id='msg-success' role='alert'><i class='fa fa-circle-x' style='font-size:25px;color:red; '></i><div class='alerta-erro'>Erro: Não foi possivel cadastrar!</div></div>";
			}
			}
			
		}
		
		?>
    </header>
	<form name="cad_op" class="form-group" method="POST" action="">
		<div class="row">
			<div class="col-md-6">
		    	<label for="inputEmail4" class="form-label">Nome do Material</label>
		    	<input type="text" name="nome" autocomplete="off" class="form-control" id="nome" reset()>	
		  </div>
		  <div class="col-md-6">
		    <label for="inputText" class="form-label">Descrição</label>
		    <input type="text" name="descricao" autocomplete="off" class="form-control" id="desc_op">
	  </div>
	</div>
	  	<div class="col-3 but-op">
	    	<input type="submit" value="Cadastrar" name="cad_reg" class="btn btn-success but-cad">
	  	</div>
	  </div>
	</form>
	<hr>
</div>
	

	
	<script src="./assets/js/custom.js"></script>

<?php
//Inclui o footer
include_once './include/footer.php';
?>