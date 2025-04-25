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
<title>Cadastrar Itens</title>
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
			<h2>Cadastrar Itens</h2>
			</div>
			<div class="row">
				<div class="col-md-2 list-icon">
				<a href="<?php echo URL ?>listar-itens"><i class="fa fa-list"></i></a>
			</div>
			<div class="col-md-2 list-icon">
				<a href="<?php echo URL ?>cadastrar-material"><i class="fa fa-clipboard"></i></a>
			</div>
			</div>
		</div>
		
	
		<?php
		if (isset($_SESSION['msg'])) {
        echo $_SESSION['msg'];
        unset($_SESSION['msg']);
    	}
		$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
		
		// Função para gerar o número de série
		function gerarNumeroSerie($item, $peca, $ordemPeca) {
    // Garante que todos os valores estejam no formato correto
    $item = strtoupper(trim($item)); // Modelo em maiúsculo e sem espaços extras
    $peca = str_pad($peca, 3,'0', STR_PAD_LEFT);   // Grupo em maiúsculo e sem espaços extras
    $ordemPeca = str_pad($ordemPeca, 2, '0', STR_PAD_LEFT); // Ordena o peca com 3 dígitos, caso necessário

    // Formato do número de série
    $numeroSerie = $item . '-' . $peca . '-' . $ordemPeca;

    return $numeroSerie;
		}

			

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
				
			//SELECT count(*) as qnt_peca from peca WHERE id_grupo = 1
			//Query peca
      $query_qnt_resgistros = "SELECT count(*) AS num_result FROM itens WHERE id_peca =" . $dados['peca'];
			$result_qnt_registros = $conn ->prepare($query_qnt_resgistros);
			$result_qnt_registros->execute();
			$row_qnt_registros = $result_qnt_registros->fetch(PDO::FETCH_ASSOC);

			
      // Exemplo de uso da função
			$item = "ITC";       // Modelo do produto
			$peca = $dados['peca'];            // Grupo do produto
			$ordemPeca = $row_qnt_registros['num_result'] + 1;         // Ordem do grupo

		// Gerar o número de série
			$numeroSerie = gerarNumeroSerie($item, $peca, $ordemPeca);


				$query_cad = "INSERT INTO itens (nome, descricao, id_material, id_peca, qtd_itens, codigo, usuario) VALUES (:nome, :descricao, :id_material, :id_peca, :qtd_itens, :codigo, :usuario)";
				$cad_reg = $conn->prepare($query_cad);
				$cad_reg->bindParam(':nome', $dados['nome'], PDO::PARAM_STR);
				$cad_reg->bindParam(':descricao', $dados['descricao'], PDO::PARAM_STR);
        $cad_reg->bindParam(':id_peca', $dados['peca'], PDO::PARAM_STR);
        $cad_reg->bindParam(':id_material', $dados['material'], PDO::PARAM_STR);
				$cad_reg->bindParam(':qtd_itens', $dados['qtd_itens'], PDO::PARAM_STR);
				$cad_reg->bindParam(':codigo', $numeroSerie, PDO::PARAM_STR);
				$cad_reg->bindParam(':usuario', $_SESSION['nome'], PDO::PARAM_STR);
				$cad_reg->execute();
			if ($cad_reg->rowCount()) {
				
				$_SESSION['msg'] = "<div id='msg-success' role='alert'><i class='fa fa-check-circle' style='font-size:25px;color:green'></i><div class='alerta-sucesso'>Registro Cadastrado!</div></div>";

				//Mudar conforme o cadastro ou alteração
				$historico = "Cadastrou Item.";
				
				//Inclui o Query historico
				include_once './include/historico.php';

				//Continuar salvar ação no historico do sistema MUDAR A CADA ALTERAÇÃO DA TABELA
				$cad_hist->bindParam(':descricao', $dados['descricao'], PDO::PARAM_STR);
				$cad_hist->execute();
				unset($dados);

				header("Location: listar-itens");
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
		    	<label for="inputEmail4" class="form-label">Nome do Item</label>
		    	<input type="text" name="nome" autocomplete="off" class="form-control" id="nome" reset()>	
		  </div>
		  <div class="col-md-6">
		    <label for="inputText" class="form-label">Descrição</label>
		    <input type="text" name="descricao" autocomplete="off" class="form-control" id="desc_op">
	  </div>
	</div>
	<div class="row">
	  <div class="form-group col-md-3">
      <label for="pecas">Item da Peça:</label>
     <!-- <input type="text" class="form-control" name="id_categorias" id="id_categorias" placeholder="Id categorias" required>-->

     <select name="peca" id="peca" class="form-control" required>
      <?php
      $query = $conn->query("SELECT id, nome FROM peca ORDER BY nome ASC");
      $result = $query->fetchAll(PDO::FETCH_ASSOC);
      ?>
      <option value="">Selecione...</option>
       <?php
      
      foreach ($result as $option) {
      ?>
        <option value="<?php echo $option['id']; ?>"><?php echo $option['nome']; ?></option>
      <?php
      }
      ?>
     </select>
    </div>
    <div class="form-group col-md-3">
      <label for="materiais">Material:</label>
     <!-- <input type="text" class="form-control" name="id_categorias" id="id_categorias" placeholder="Id categorias" required>-->

     <select name="material" id="material" class="form-control" required>
      <?php
      $query = $conn->query("SELECT id, nome FROM material ORDER BY nome ASC");
      $result = $query->fetchAll(PDO::FETCH_ASSOC);
      ?>
      <option value="">Selecione...</option>
       <?php
      
      foreach ($result as $option) {
      ?>
        <option value="<?php echo $option['id']; ?>"><?php echo $option['nome']; ?></option>
      <?php
      }
      ?>
     </select>
     	  <div class="col-2 but-mais">
	  		<a href="<?php echo URL ?>cadastrar-material"><i class="fa fa-plus"></i></a>
    </div>
    </div>
          
    <div class="col-md-2">
		    <label for="inputText" class="form-label">Quantidade por Peça</label>
		    <input type="number" name="qtd_itens" autocomplete="off" class="form-control" id="qtd_itens">
	  </div>
	 
	  	<div class="col-2 but-op">
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