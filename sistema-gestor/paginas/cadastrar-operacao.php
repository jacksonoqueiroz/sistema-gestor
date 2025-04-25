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
<title>Cadastrar Operação</title>
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
				
			<h2>Cadastrar Operação</h2>
			</div>
			<div class="col-md-3 list-icon">
				<a href="<?php echo URL ?>listar-operacao"><i class="fa fa-list"></i></a>
			</div>
			
		</div>
		
	
		<?php
		if (isset($_SESSION['msg'])) {
        echo $_SESSION['msg'];
        unset($_SESSION['msg']);
    	}
		$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

		if (!empty($dados['cad_op'])) {
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
				$query_op = "INSERT INTO tipo_operacao (nome, desc_op, cod_op, usuario) VALUES (:nome, :desc_op, :cod_op, :usuario)";
				$cad_op = $conn->prepare($query_op);
				$cad_op->bindParam(':nome', $dados['nome'], PDO::PARAM_STR);
				$cad_op->bindParam(':desc_op', $dados['desc_op'], PDO::PARAM_STR);
				$cad_op->bindParam(':cod_op', $dados['cod_op'], PDO::PARAM_STR);
				$cad_op->bindParam(':usuario', $_SESSION['nome'], PDO::PARAM_STR);
				$cad_op->execute();
			if ($cad_op->rowCount()) {
				
				$_SESSION['msg'] = "<div id='msg-success' role='alert'><i class='fa fa-check-circle' style='font-size:25px;color:green'></i><div class='alerta-sucesso'>Registro Cadastrado!</div></div>";

				//Mudar conforme o cadastro ou alteração
				$historico = "Cadastrou Operação.";
				
				//Inclui o Query historico
				include_once './include/historico.php';

				//Continuar salvar ação no historico do sistema MUDAR A CADA ALTERAÇÃO DA TABELA
				$cad_hist->bindParam(':descricao', $dados['desc_op'], PDO::PARAM_STR);
				$cad_hist->execute();

				unset($dados);

				header("Location: listar-operacao");
			}else{
				echo $_SESSION['msg'] = "<div class='alert alert-danger' id='msg-success' role='alert'><i class='fa fa-circle-x' style='font-size:25px;color:red; '></i><div class='alerta-erro'>Erro: Não foi possivel cadastrar!</div></div>";
			}
			}
			
		}
		
		?>
    </header>
	<form name="cad_op" class="form-group" method="POST" action="">
		<div class="row">
			<input type="hidden" name="historico" value="Cadastrou Tipo de Operação.">
			<div class="col-md-6">
		    	<label for="inputEmail4" class="form-label">Nome da Operação</label>
		    	<input type="text" name="nome" autocomplete="off" class="form-control" id="nome" reset()>	
		  </div>
		  <div class="col-md-3">
		    <label for="inputText" class="form-label">Descrição</label>
		    <input type="text" name="desc_op" autocomplete="off" class="form-control" id="desc_op">
	  </div>
	  <div class="col-md-3">
		    <label for="inputPassword4" class="form-label">Código da Operação</label>
		    <input type="text" name="cod_op" autocomplete="off" class="form-control" id="cod_op">
	  </div>	  
	  	<div class="col-3 but-op">
	    	<input type="submit" value="Cadastrar" name="cad_op" class="btn btn-success but-cad">
	  	</div>
	  </div>
	  <hr>
	</form>
	</div>
	<!------------------- LISTA DE OPERAÇÃO ------------------------------------>
 <div class="container py-4">
 <h2>Lista</h2>
	 <table class="table table-striped">
            <thead>
              <tr>
                <th scope="col">Operação</th>
                <th scope="col">Código</th>
                <th scope="col">Ação</th>
              </tr>
            </thead>

            <tbody>

    <?php
    //Paginação
    
    //Reber o número da página
    $pagina_atual = filter_input(INPUT_GET, "page", FILTER_SANITIZE_NUMBER_INT);
    $pagina = (!empty($pagina_atual)) ? $pagina_atual : 1;

    //Setar a quantidade de registros por página
    $limite_resultado = 2;

    //Calcular o inicio da visualização
    $inicio = ($limite_resultado * $pagina) - $limite_resultado;



    $query_op = "SELECT id, nome, cod_op FROM tipo_operacao LIMIT $inicio, $limite_resultado";
		$result_op = $conn->prepare($query_op);
		$result_op->execute();

		if (($result_op) AND ($result_op->rowCount() != 0)) {
			while ($row_op = $result_op->fetch(PDO::FETCH_ASSOC)) {
				extract($row_op)
				?> 

              <tr>                
                <td><?php echo $nome ?></td>
                <td><?php echo $cod_op ?></td>
                <td>Ação</td>
              </tr>
             
        <?php
			}

			
		}else{
			echo $_SESSION['msg'] = "<div class='alert alert-danger' id='msg-success' role='alert'><i class='fa fa-circle-x' style='font-size:25px;color:red'></i><div class='alerta-erro'>Nenhum Registro encontrado!</div></div>";
			
		}
		?>
		</tbody>
		</table>
		<?php
		//Contar a quantidade de registros no Banco de Dados
			$query_qnt_resgistros = "SELECT count(id) AS num_result FROM tipo_operacao";
			$result_qnt_registros = $conn ->prepare($query_qnt_resgistros);
			$result_qnt_registros->execute();
			$row_qnt_registros = $result_qnt_registros->fetch(PDO::FETCH_ASSOC);
			
			//Quantidade de página(ceil arredonda para mais caso valor decimal)
			$qnt_pagina = ceil($row_qnt_registros['num_result'] / $limite_resultado);

			// Máximo de Link
			$maximo_link = 2;

	?>

	<nav aria-label="...">
  <ul class="pagination justify-content-center">
    <li class="page-item">
      <?php echo "<a class='page-link' href='cadastrar-operacao?page=1'>Primeira</a> "; ?>
    </li>
    <?php 
    	for ($pagina_anterior = $pagina - $maximo_link; $pagina_anterior <= $pagina -1 ; $pagina_anterior++) { 
    		?>
    <li class="page-item">
    	<?php
				if ($pagina_anterior >= 1) {
					echo "<a class='page-link' href='cadastrar-operacao?page=$pagina_anterior'>$pagina_anterior</a> ";
				}
				 
			}

			?>
    </li>
    <li class="page-item active">
      <a class="page-link" href="#"><?php echo "$pagina"; ?> <span class="sr-only">(atual)</span></a>
    </li>
    <?php 
    	for ($proxima_pagina = $pagina + 1; $proxima_pagina <= $pagina + $maximo_link; $proxima_pagina++) {?>
		<li class="page-item">
    		<?php
							if($proxima_pagina <= $qnt_pagina){
								echo "<a class='page-link' href='cadastrar-operacao?page=$proxima_pagina'> $proxima_pagina</a> ";
							}
				
			}
    ?>
   </li>
    <li class="page-item">
      <?php echo "<a class='page-link' href='cadastrar-operacao?page=$qnt_pagina'>Última</a>"; ?>
    </li>
  </ul>
</nav>

</div>
	<!---------------FIM DA LISTA DE OPERAÇÃO ------------------------------------>

	
	<script src="./assets/js/custom.js"></script>

<?php
//Inclui o footer
include_once './include/footer.php';
?>