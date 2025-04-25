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
<title>Cadastrar Ordem</title>
</head>
<?php

//Inclui o menu
include_once './include/menu.php';
 //echo "Página cadastro.";

?>
<div class="container py-4 cadastro">
	<header class="pb-3 mb-4 border-bottom">
	
		<div class="row">
			<div class="col-md-5">
			<h2>Ordem de produção</h2>
			</div>
			<div class="row">
				<div class="col-md-2 list-icon">
				<a href="<?php echo URL ?>listar-ordens"><i class="fa fa-list"></i></a>
			</div>
			</div>
		</div>
		
	
		<?php
		if (isset($_SESSION['msg'])) {
        echo $_SESSION['msg'];
        unset($_SESSION['msg']);
    	}
		$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
		
			// Data atual
			$mes = date('m'); // Mês com 2 dígitos
			$ano = date('y'); // Ano com 2 dígitos

			// Simulando uma sequência de produção (ex: de um banco ou arquivo)
			// Aqui vamos apenas gerar aleatoriamente para exemplo
			$sequencia = rand(1, 9999);

			// Formatando a sequência com zeros à esquerda
			$sequenciaFormatada = str_pad($sequencia, 4, '0', STR_PAD_LEFT);

			// Gerando número de série final
			$numeroSerie = $mes . $ano . $sequenciaFormatada;

			// echo "Número de série: $numeroSerie";

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
				
				$query_cad = "INSERT INTO ordem_producao (id_modelo, motivo, qtd_itens, codigo, usuario) VALUES (:id_modelo, :motivo, :qtd_itens, :codigo, :usuario)";
				$cad_reg = $conn->prepare($query_cad);
        $cad_reg->bindParam(':id_modelo', $dados['modelo'], PDO::PARAM_STR);
				$cad_reg->bindParam(':motivo', $dados['motivo'], PDO::PARAM_STR);
				$cad_reg->bindParam(':qtd_itens', $dados['qtd_itens'], PDO::PARAM_STR);
				$cad_reg->bindParam(':codigo', $numeroSerie, PDO::PARAM_STR);
				$cad_reg->bindParam(':usuario', $_SESSION['nome'], PDO::PARAM_STR);
				$cad_reg->execute();
			if ($cad_reg->rowCount()) {
				
				$_SESSION['msg'] = "<div id='msg-success' role='alert'><i class='fa fa-check-circle' style='font-size:25px;color:green'></i><div class='alerta-sucesso'>Registro Cadastrado!</div></div>";

				//Mudar conforme o cadastro ou alteração
				$historico = "Cadastrou Ordem de Produção.";
				
				//Inclui o Query historico
				include_once './include/historico.php';

				//Continuar salvar ação no historico do sistema MUDAR A CADA ALTERAÇÃO DA TABELA
				$cad_hist->bindParam(':descricao', $dados['motivo'], PDO::PARAM_STR);
				$cad_hist->execute();
				unset($dados);

				header("Location: listar-ordens");
			}else{
				echo $_SESSION['msg'] = "<div class='alert alert-danger' id='msg-success' role='alert'><i class='fa fa-circle-x' style='font-size:25px;color:red; '></i><div class='alerta-erro'>Erro: Não foi possivel cadastrar!</div></div>";
			}
			}
			
		}
		
		?>
    </header>
	<form name="cad_op" class="form-group" method="POST" action="">
		<div class="row">
		<div class="form-group col-md-3">
      <label for="pecas">Modelo:</label>
     <select name="modelo" id="modelo" class="form-control" required>
      <?php
      $query = $conn->query("SELECT id, nome FROM produtos ORDER BY nome ASC");
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
			<div class="col-md-4">
		    	<label for="inputEmail4" class="form-label">Motivo</label>
		    	<input type="text" name="motivo" autocomplete="off" class="form-control" id="motivo">	
		  </div>
    <div class="col-md-2">
		    <label for="inputText" class="form-label">Quantidade</label>
		    <input type="text" name="qtd_itens" autocomplete="off" class="form-control" id="qtd_itens">
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