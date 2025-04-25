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
<title>Listar Operação</title>
</head>
<?php

//Inclui o menu
include_once './include/menu.php';
 //echo "Página cadastro.";

?>
<div class="container py-4">
	<header class="pb-3 mb-4">		
		
		<div class="row">
			<div class="col-md-4">
				
			<h2>Lista de Operações</h2>
			</div>
			<div class="col-md-3 list-icon">
				<a href="<?php echo URL ?>cadastrar-operacao"><i class="fa fa-clipboard"></i></a>
			</div>
			
		</div>
    <?php 
    	if(isset ($_SESSION['msg'])){
    		echo $_SESSION['msg'];
    		unset($_SESSION['msg']);
    	}
    ?>
    <table class="table table-striped">
            <thead>
              <tr>
                <th scope="col-3">Operação</th>
                <th scope="col-2">Código</th>
                <th colspan>Ação</th>
              </tr>
            </thead>

            <tbody>
    <?php

    //Paginação
    
    //Reber o número da página
    $pagina_atual = filter_input(INPUT_GET, "page", FILTER_SANITIZE_NUMBER_INT);
    $pagina = (!empty($pagina_atual)) ? $pagina_atual : 1;
    
    // echo "<pre>";
    // var_dump($pagina);
    // echo "</pre>";

    //Setar a quantidade de registros por página
    $limite_resultado = 3;

    //Calcular o inicio da visualização
    $inicio = ($limite_resultado * $pagina) - $limite_resultado;
    
    $query_op = "SELECT id, nome, cod_op, desc_op FROM tipo_operacao LIMIT $inicio, $limite_resultado";
		$result_op = $conn->prepare($query_op);
		$result_op->execute();

		if (($result_op) AND ($result_op->rowCount() != 0)) {
			while ($row_op = $result_op->fetch(PDO::FETCH_ASSOC)) {
				// echo "<pre>";
				// var_dump($row_op);
				// echo "</pre>";
				extract($row_op)
				?> 

              <tr>                
                <td><?php echo $nome ?></td>
                <td><?php echo $cod_op ?></td>
                <td class="row">
                	<div class="col-2 action-icon">
                		<a href="<?php echo URL . 'detalhes-operacao?id_op=' . $id ?>"><i class="fa fa-eye"></i></a>
                		</div>
                	<div class="col-2 action-icon">
                		<a href="<?php echo URL . 'editar-operacao?id_op=' . $id ?>"><i class="fa fa-pen"></i></a>
                		</div>
                	<!-- <div class="col-2 action-icon">
                			<a href="<?php echo URL . 'excluir-operacao?id_op=' . $id . ', nome=' . $nome ?>"><i class="fa fa-trash" onclick=""></i></a>
                		</div> -->
                		<div class="col-2 action-icon">
                			<a onclick="apagarRegistro(<?php echo $id ?>)" href="<?php echo URL . 'excluir-operacao?id_op=' . $id . ', nome=' . $nome ?>"><i class="fa fa-trash"></i></a>
                		</div>
                </td>
                </tr>
               
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

			// echo "<a href='listar-operacao?page=1'>Primeira</a> ";

			for ($pagina_anterior = $pagina - $maximo_link; $pagina_anterior <= $pagina -1 ; $pagina_anterior++) {
				if ($pagina_anterior >= 1) {
					// echo "<a href='listar-operacao?page=$pagina_anterior'>$pagina_anterior</a> ";
				}
				 
			}

			// echo "$pagina";

			for ($proxima_pagina = $pagina + 1; $proxima_pagina <= $pagina + $maximo_link; $proxima_pagina++) {
							if($proxima_pagina <= $qnt_pagina){
								// echo "<a href='listar-operacao?page=$proxima_pagina'> $proxima_pagina </a> ";
							}
				
			}

			// echo "<a href='listar-operacao?page=$qnt_pagina'> Última</a> ";
	?>

	<nav aria-label="...">
  <ul class="pagination">
    <li class="page-item">
      <!-- <a class="page-link" href="#" tabindex="-1">Anterior</a> -->
      <?php echo "<a class='page-link' href='listar-operacao?page=1'>Primeira</a> "; ?>
    </li>
    <?php for ($pagina_anterior = $pagina - $maximo_link; $pagina_anterior <= $pagina -1 ; $pagina_anterior++) { ?>
    <li class="page-item">
    	<!-- <a class="page-link" href="#">1</a> -->
    	<?php
				if ($pagina_anterior >= 1) {
					echo "<a class='page-link' href='listar-operacao?page=$pagina_anterior'>$pagina_anterior</a> ";
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
								echo "<a class='page-link' href='listar-operacao?page=$proxima_pagina'> $proxima_pagina </a> ";
							}
				
			}
    ?>
   </li>
    <li class="page-item">
      <?php echo "<a class='page-link' href='listar-operacao?page=$qnt_pagina'>Última</a> "; ?>
    </li>
  </ul>
</nav>

	
	<script src="./assets/js/custom.js"></script>

<?php
//Inclui o footer
include_once './include/footer.php';
?>