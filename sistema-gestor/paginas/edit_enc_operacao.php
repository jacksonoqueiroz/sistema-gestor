<?php

session_start();
ob_start();

include_once ('conexao/conexao.php');
$id_peca = filter_input(INPUT_GET, "id_peca", FILTER_SANITIZE_NUMBER_INT);
$id_op = filter_input(INPUT_GET, "id_op", FILTER_SANITIZE_NUMBER_INT);
$ordem = filter_input(INPUT_GET, "ordem", FILTER_SANITIZE_NUMBER_INT);
// echo "<pre>";
// var_dump($id_peca);
// echo "</pre>";
// echo "<pre>";
// var_dump($id_op);
// echo "</pre>";
// echo "<pre>";
// var_dump($ordem);
// echo "</pre>";

if (empty($id_op)) {
	$_SESSION['msg'] = "<div class='alert alert-danger' id='msg-success' role='alert'><i class='fa fa-circle-x' style='font-size:25px;color:red'></i><div class='alerta-erro'>Selecione uma operração!</div></div>";	
	header("Location: listar-ordens");
	exit();
}

if ((!isset($_SESSION['id'])) AND (!isset($_SESSION['nome']))) {
	$_SESSION['msg'] = '<div class="alert alert-danger" role="alert">É necessário realizar o Login, para acessar o sistema!</div>';	
	header("Location: login.php");
}

// $query_op = "SELECT id, id_operacao, id_peca FROM inserir_op WHERE id_peca = $id_peca AND id_operacao = $id_op";
// $result_op = $conn->prepare($query_op);
// $result_op->execute();
// $row_op = $result_op->fetch(PDO::FETCH_ASSOC);

$query_op = "SELECT in_op.id AS in_op,
			in_op.id_operacao,
			in_op.id_peca,
			peca.nome AS peca, 
            tipo_operacao.nome AS operacao,
            tipo_operacao.cod_op AS cod_op,
            in_op.tempo AS hora_prev, 
            in_op.hora_inicio,
            in_op.hora_fim,
            in_op.eficiencia,
            tipo_operacao.cod_op AS codigo 
FROM encerra_operacao AS in_op 
INNER JOIN peca AS peca ON peca.id=in_op.id_peca 
INNER JOIN tipo_operacao AS tipo_operacao ON tipo_operacao.id=in_op.id_operacao WHERE id_peca = $id_peca AND id_operacao = $id_op";
$result_op = $conn->prepare($query_op);
$result_op->execute();
$row_op = $result_op->fetch(PDO::FETCH_ASSOC);
extract($row_op);

// Função para converter um horário (string) em segundos
function horarioParaSegundos($horario) {
    list($hora, $minuto, $segundo) = explode(':', $horario);
    return ($hora * 3600) + ($minuto * 60) + $segundo;
}

// Função para calcular a porcentagem
function calcularPorcentagem($parte, $total) {
    // return ($parte / $total) * 100;
	return ($total / $parte) * 100;
}

// Exemplo de uso
$horarioInicial = $hora_inicio;
$horarioFinal = $hora_fim;
$totalHoras = $hora_prev; // Total de horas em um dia

// Converte os horários para segundos
$segundosInicial = horarioParaSegundos($horarioInicial);
$segundosFinal = horarioParaSegundos($horarioFinal);
$totalSegundos = horarioParaSegundos($totalHoras);

// Calcula o total de segundos entre o início e o final
$totalSegundosIntervalo = $segundosFinal - $segundosInicial;

// Calcula a porcentagem
$eficiencia = calcularPorcentagem($totalSegundosIntervalo, $totalSegundos);





//Inclui o head
include_once './include/head.php';
?>
<title>Editar | Encerrar Operação</title>
</head>
				

<?php


//Inclui o menu
include_once './include/menu.php';
 //echo "Página cadastro.";

$query_reg = "SELECT inserir_ordem.id_ordem,
                            ordem.codigo AS ordem,
                            peca.nome AS peca,
                            peca.codigo AS cod_peca,
                            inserir_ordem.qtd,
                            inserir_ordem.status
                FROM inserir_ordem AS inserir_ordem
                INNER JOIN peca AS peca ON peca.id=inserir_ordem.id_peca
                INNER JOIN ordem_producao AS ordem ON ordem.id=inserir_ordem.id_ordem WHERE peca.id = $id_peca";
			$result_reg = $conn->prepare($query_reg);
			$result_reg->execute();
			$row_reg = $result_reg->fetch(PDO::FETCH_ASSOC);
			extract($row_reg);



?>
	<div class="container py-4">
		<header class="pb-3 mb-4 border-bottom">
			<div class="cabecalho-of">
			<p>Editar - Encerrar Operação</p>
			
			<?php
				//Receber os Dados do formulário
			$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
			// $status_enc ="ENC";
			
			if (!empty($dados['cad_reg'])) {
				echo "<pre>";
				var_dump($dados);
				echo "</pre>";
				$query_up_op = "UPDATE encerra_operacao SET hora_inicio=:hora_inicio, hora_fim=:hora_fim, eficiencia=:eficiencia WHERE id_operacao=:id_operacao AND id_peca=:id_peca AND id_ordem=:id_ordem";
				$edit_op= $conn->prepare($query_up_op);
				$edit_op->bindParam(':hora_inicio', $dados['hora_inicio'], PDO::PARAM_STR);
				$edit_op->bindParam(':hora_fim', $dados['hora_fim'], PDO::PARAM_STR);
				$edit_op->bindParam(':eficiencia', $dados['eficiencia'], PDO::PARAM_STR);
					$edit_op->bindParam(':id_operacao', $id_operacao, PDO::PARAM_INT);
					$edit_op->bindParam(':id_peca', $id_peca, PDO::PARAM_INT);
					$edit_op->bindParam(':id_ordem', $id_ordem, PDO::PARAM_INT);

				if ($edit_op->execute()) {
					$_SESSION['msg'] = "<div id='msg-success' role='alert'><i class='fa fa-check-circle' style='font-size:25px;color:green'></i><div class='alerta-sucesso'>Registro Alterado!</div></div>";
					header("location: peca-ordem?id_ordem=" .$id_ordem);
						
				}else{
					echo $_SESSION['msg'] = "<div class='alert alert-danger' id='msg-success' role='alert'><i class='fa fa-circle-x' style='font-size:25px;color:red; '></i><div class='alerta-erro'>Erro: Não foi alterado!</div></div>";
				}



			}
			?>				
			
			</div>
			<div class="row">
				<div class="col-md-4 titulo-of">
					<p>Peça: <strong><?php 
					if (isset($row_reg['peca'])) {
						// echo $peca;
						echo $row_reg['peca'];
					}
					 ?></strong></p>				
				</div>
			</div>
			<div class="row">
				<div class="col-md-4">
					<p>Ordem: <strong><?php 
					if (isset($row_reg['ordem'])) {
						// echo $ordem
						echo $row_reg['ordem'];
					}

					 ?></strong></p>				
				</div>
			</div>
		</header>
		<div class="text-center titulo-of">
					<p>Eficiência: <strong><?php echo round($eficiencia, 2); ?>%</strong></p>
					
			</div>
				
	<form name="cad_op" class="form-group" method="POST" action="">
		<div class="row">
		
		    <input type="hidden" name="eficiencia" autocomplete="off" class="form-control" id="eficiencia" reset() value="<?php echo round($eficiencia, 2); ?>" ReadOnly>	
					
			<div class="col-md-1">
		    	<label for="inputEmail4" class="form-label">Código</label>
		    	<input type="text" name="cod_op" autocomplete="off" class="form-control" id="cod_op" reset() value="<?php 

		    	echo $cod_op ?>" ReadOnly>	
		  </div>
		  <div class="col-md-3">
		    	<label for="inputEmail4" class="form-label">Operação</label>
		    	<input type="text" name="operacao" autocomplete="off" class="form-control" id="operacao" reset() value="<?php echo $operacao ?>"ReadOnly>	
		  </div>
		  <div class="col-md-2">
		    <label for="inputText" class="form-label">Tempo previsto</label>
		    <input type="time" name="tempo" autocomplete="off" class="form-control" id="tempo" value="<?php echo $hora_prev; ?>"ReadOnly>
	  	</div>
	  	<div class="col-md-2">
		    <label for="inputText" class="form-label">Hora Início</label>
		    <input type="time" name="hora_inicio" autocomplete="off" class="form-control" id="hora_inicio" value="<?php 
		    	if (isset($dados['hora_inicio'])) {
		    		echo $dados['hora_inicio'];
		    	}else{
		    		echo $hora_inicio;
		    	}

		     ?>">
	  	</div>
	  	<div class="col-md-2">
		    <label for="inputText" class="form-label">Hora Final</label>
		    <input type="time" name="hora_fim" autocomplete="off" class="form-control" id="hora_fim" value="<?php 
		    	if (isset($dados['hora_fim'])) {
		    		echo $dados['hora_fim'];
		    	}else{
		    		 echo $hora_fim;
		    	}

		    ?>">
	  	</div>

	  	<div class="col-2 but-op">
	    	<input type="submit" value="Confirmar" name="cad_reg" class="btn btn-success but-cad">
	  	</div>
	</div>
	<br>
	  	<hr>
	</form>


		
		
	</div>

<?php
//Inclui o footer
include_once './include/footer.php';
?>