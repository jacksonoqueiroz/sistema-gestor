<?php

session_start();
ob_start();

include_once ('conexao/conexao.php');

$id_operacao = filter_input(INPUT_GET, "id_operacao", FILTER_SANITIZE_NUMBER_INT);
$id_peca = filter_input(INPUT_GET, "id_peca", FILTER_SANITIZE_NUMBER_INT);
$id_ordem = filter_input(INPUT_GET, "id_ordem", FILTER_SANITIZE_NUMBER_INT);
// echo "<pre>";
// var_dump($id_opercao);
// echo "</pre>";
// echo "<pre>";
// var_dump($id_peca);
// echo "</pre>";

if (empty($id_peca)) {
	$_SESSION['msg'] = "<div class='alert alert-danger' id='msg-success' role='alert'><i class='fa fa-circle-x' style='font-size:25px;color:red'></i><div class='alerta-erro'>Selecione uma operação!</div></div>";	
	header("Location: listar-peca");
	exit();
}

if ((!isset($_SESSION['id'])) AND (!isset($_SESSION['nome']))) {
	$_SESSION['msg'] = '<div class="alert alert-danger" role="alert">É necessário realizar o Login, para acessar o sistema!</div>';	
	header("Location: login.php");
}


//Inclui o head
include_once './include/head.php';
?>
<title>Eficiência</title>
</head>
<?php

//Inclui o menu
include_once './include/menu.php';



			$query_op = "SELECT in_op.id AS id_in_operacao, 
										peca.nome AS peca,
										in_op.id_operacao,
                		tipo_operacao.nome AS operacao,
                		in_op.tempo AS hora_prev,
                		in_op.hora_inicio AS inicio,
                		in_op.hora_fim AS final,
                		tipo_operacao.cod_op AS codigo
    FROM encerra_operacao AS in_op 
    INNER JOIN peca AS peca 
    ON peca.id=in_op.id_peca
    INNER JOIN tipo_operacao AS tipo_operacao 
    ON tipo_operacao.id=in_op.id_operacao WHERE id_operacao = $id_operacao";
			$result_op = $conn->prepare($query_op);
			$result_op->execute();

			if (($result_op) AND ($result_op->rowCount() != 0)) {
				$row_op = $result_op->fetch(PDO::FETCH_ASSOC);
				// echo "<pre>";
				// var_dump($row_op);
				// echo "</pre>";
				extract($row_op);
			}
	//Converter os Dois horário para um objeto DateTime do PHP
	$entrada = DateTime::createFromFormat('H:i:s', $inicio);
	$saida = DateTime::createFromFormat('H:i:s', $final);
	$tempo = DateTime::createFromFormat('H:i:s', $hora_prev);

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
$horarioInicial = $inicio;
$horarioFinal = $final;
$totalHoras = $hora_prev; // Total de horas em um dia

// Converte os horários para segundos
$segundosInicial = horarioParaSegundos($horarioInicial);
$segundosFinal = horarioParaSegundos($horarioFinal);
$totalSegundos = horarioParaSegundos($totalHoras);

// Calcula o total de segundos entre o início e o final
$totalSegundosIntervalo = $segundosFinal - $segundosInicial;

// Calcula a porcentagem
$eficiencia = calcularPorcentagem($totalSegundosIntervalo, $totalSegundos);


		?>
	<div class="container py-4">
		<header class="pb-3 mb-4">
			<div class="cabecalho-of">
				<p>Eficiência</p>
			</div>
			<div class="row">				
				<div class="col-md-5 titulo-of">
					<!-- <p class="titulo-peca"><?php echo $peca; ?></p> -->
					<p>Peça: <strong><?php echo $peca; ?></strong> </p>
				</div>
				<div class="col-4 titulo-of">
					<p>Operação:<strong> <?php echo $operacao;  ?></strong></p>
				</div>
				<div class="col-md-3 titulo-of">
					<p>Tempo Previsto: <strong><?php echo $hora_prev; ?></strong> </p>
				</div>
			</div>
			<br>
			<div class="text-center titulo-of">				
					<p>Hora Início: <strong><?php echo $inicio; ?></strong> </p>
			</div>
			<div class="text-center titulo-of">
					<p>Hora Final: <strong><?php echo $final; ?></strong> </p>
			</div>		
			<div class="text-center titulo-of">
					<p>Eficiência: <strong><?php echo round($eficiencia, 2); ?>%</strong></p>
			</div>
			<br>
			<form>
					<div class="text-center titulo-of">
						<a href="<?php echo URL . 'edit_enc_operacao?id_peca=' . $id_peca . '&id_op=' . $id_operacao . '&ordem=' . $id_ordem ?>" class="btn btn-outline-warning btn-lg">Confirmar</a>
					</div>
			</form>
						
			<hr>
		</header>

		<!-- eficiencia?id_peca=1&id_operacao=20 -->

		
</div>
<?php
//Inclui o footer
include_once './include/footer.php';
?>