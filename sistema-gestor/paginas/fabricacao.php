<?php

session_start();
ob_start();

include_once ('conexao/conexao.php');

$id_ordem = filter_input(INPUT_GET, "id_ordem", FILTER_SANITIZE_NUMBER_INT);
$id_peca = filter_input(INPUT_GET, "id_peca", FILTER_SANITIZE_NUMBER_INT);
// echo "<pre>";
// var_dump($id_ordem);
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
<title>OF's | Ordem de Fabricação</title>
</head>
<?php

//Inclui o menu
include_once './include/menu.php';

			$query_op = "SELECT inserir_ordem.id_ordem,
                            ordem.codigo AS ordem,
                            peca.nome AS peca,
                            peca.codigo AS cod_peca,
                            inserir_ordem.qtd AS qtd_or,
                            inserir_ordem.status
                FROM inserir_ordem AS inserir_ordem
                INNER JOIN peca AS peca ON peca.id=inserir_ordem.id_peca
                INNER JOIN ordem_producao AS ordem ON ordem.id=inserir_ordem.id_ordem WHERE peca.id = $id_peca";
			$result_op = $conn->prepare($query_op);
			$result_op->execute();

			if (($result_op) AND ($result_op->rowCount() != 0)) {
				$row_op = $result_op->fetch(PDO::FETCH_ASSOC);
				// echo "<pre>";
				// var_dump($row_op);
				// echo "</pre>";
				extract($row_op);
			}
		?>
	<div class="container py-4">
		<header class="pb-3 mb-4">
			<div class="cabecalho-of">
				<p>ORDEM DE FABRICAÇÃO - OF'S</p>
			</div>
			<div class="row">
				<div class="col-md-3 titulo-of">
					<!-- <p class="titulo-peca"><?php echo $peca; ?></p> -->
					<p>Peça: <strong><?php echo $peca; ?></strong> </p>
				</div>
				<div class="col-md-4 titulo-of">
					<p>Código da Peça: <strong><?php echo $cod_peca; ?></strong> </p>
				</div>
				<div class="col-md-4 titulo-of">
					<p>Ordem: <strong><?php echo $ordem; ?></strong> </p>
				</div>
				<div class="col-md-1 titulo-of">
					<p>Qtd: <strong><?php echo $qtd_or; ?></strong> </p>
				</div>
			</div>
			<div class="row">
				
			</div>			
			<hr>
		</header>

		<!---------TABELA QUE RECUPERA ITENS-------->
		
		<table class="table table-borderless tab-itens w-auto">
		  <thead>  	
		    <tr>
		      <th>Itens:</th><!-- 
		      <th scope="col">Código</th>
		      <th scope="col">Nome</th>
		      <th scope="col">Qtd</th> -->
		    </tr>
		  </thead>
		  <tbody>

<?php
		  	// Recuperar os Registro  do Itens
    $query_reg = "SELECT item.id AS id_item, 
				item.nome,
				item.descricao, 
                item.qtd_itens AS qtd, 
                item.codigo,
                peca.nome AS peca
          FROM itens AS item 
          INNER JOIN peca AS peca 
          ON peca.id=item.id_peca WHERE id_peca = $id_peca";
    $result_reg = $conn->prepare($query_reg);
    $result_reg->execute();

    

		if (($result_reg) AND ($result_reg->rowCount() != 0)) {
			while ($row_reg = $result_reg->fetch(PDO::FETCH_ASSOC)) {
				// echo "<pre>";
				// var_dump($row_op);
				// echo "</pre>";
				extract($row_reg);
				$qtd_n = ($qtd * $qtd_or);
				?> 

		    <tr>
		      <th scope="row"></th>
		      <td><?php echo $codigo ?></td>           
              <td><?php echo $nome ?></td>
              <td><?php echo $descricao ?></td>
              <td><?php echo $qtd_n ?></td>
		    </tr>
		            <?php
			}
		}
			?>

		  </tbody>
				</table>
					<br>
		<hr style="margin-top: -15px;">
		<!--------------------------FIM TABELA ITENS-------->

		<!------------TABELA OPERAÇÃO--------------->
		<!-- <div>
			<h4>Processos</h4>
		</div>
 -->
		<table class="table table-borderless tab-op w-">
		  <thead>  	
		    <tr>
		    <th scope="row">Processos</th>
		      <th scope="col">Código</th>
		      <th scope="col">Operação</th>
		      <th scope="col">Descrição</th>
		      <th scope="col">Hora</th>
		      <th scope="col">Status</th>
		      <th scope="col">Ação</th>
		    </tr>
		  </thead>
		  <tbody>

<?php
		  	// Recuperar os GRUPOS
    $query_reg = "SELECT in_op.id AS id_in_operacao, 
										peca.nome AS peca,
										in_op.id_operacao,
                		tipo_operacao.nome AS operacao,
                		in_op.descricao AS descricao,
                		in_op.tempo AS hora,
                		in_op.status AS status,
                		tipo_operacao.cod_op AS codigo
    FROM inserir_op AS in_op 
    INNER JOIN peca AS peca 
    ON peca.id=in_op.id_peca
    INNER JOIN tipo_operacao AS tipo_operacao 
    ON tipo_operacao.id=in_op.id_operacao WHERE id_peca = $id_peca";
    $result_reg = $conn->prepare($query_reg);
    $result_reg->execute();

    // $status_enc = "ENC";

		if (($result_reg) AND ($result_reg->rowCount() != 0)) {
			while ($row_reg = $result_reg->fetch(PDO::FETCH_ASSOC)) {
				// echo "<pre>";
				// var_dump($row_op);
				// echo "</pre>";
				extract($row_reg);

				$time = $hora; // Exemplo de hora
				$mult = $qtd_or; // Valor para multiplicar

				// Converter a hora em segundos
				$segundos = strtotime('1970-01-01 ' . $time . ' UTC');

				// Multiplicar por um fator
				$resultado = ($segundos * $mult)-3600;

				?> 

		    <tr>
		     <td></td>
		      <td><?php echo $codigo ?></td>           
              <td><?php echo $operacao ?></td>
              <td><?php echo $descricao ?></td>
              <!-- <td><?php echo $hora ?></td> -->
              <td><?php echo date("h:i:s", $resultado); ?></td>
              <td><?php echo $status ?></td>
              <?php
              	if ($status != "ENC") {
              		?>
              		<td> <a href="<?php echo URL . 'enc_operacao?id_peca=' . $id_peca . '&id_op=' . $id_operacao . '&ordem=' . $id_ordem?>" class="btn btn-primary">Encerrar</a></td>
              		<?php
              	}

              ?>
              
            <?php
            ?>

		    </tr>
		            <?php
			}
		}else{
			echo $_SESSION['msg'] = "<div class='alert alert-danger' id='msg-success' role='alert'><i class='fa fa-circle-x' style='font-size:25px;color:red'></i><div class='alerta-erro'>Nenhum Registro encontrado!</div></div>";			
		}
			?>

		  </tbody>
				</table>
					<br>
		<hr style="margin-top: -15px;">
<!---------------------------------FIM TABELA OPERAÇÃO---------------->
	
	<!-------- ANEXO DOCUMENTOS E DESENHOS  ----------->
	<div>
		Anexos:
	</div>

<!-------- BOTÃO MODAl ANEXO DESENHO LATERAL DIREITO ----------->

<button type="button" class="btn btn-primary">
  <i class="fa fa-file-pdf" aria-hidden="true"></i></a>
  <a href="assets/arquivo/estrutura/corte_dobra.pdf" class="botao" target="_blank">
</button>

 <!-----------------FIM DO ANEXO------------------------>

 
</div>
<?php
//Inclui o footer
include_once './include/footer.php';
?>