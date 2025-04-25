<?php
ob_start();
session_start();

include_once ('conexao/conexao.php');

$id_peca = filter_input(INPUT_GET, "id_peca", FILTER_SANITIZE_NUMBER_INT);

if ((!isset($_SESSION['id'])) AND (!isset($_SESSION['nome']))) {
	$_SESSION['msg'] = '<div class="alert alert-danger" role="alert">É necessário realizar o Login, para acessar o sistema!</div>';	
	header("Location: login.php");
}
//Inclui o head
include_once './include/head.php';
?>
<title>Operações por Peça</title>
</head>
<?php

//Inclui o menu
include_once './include/menu.php';
 //echo "Página cadastro.";

?>
<div class="container py-4">
	<header class="pb-3 mb-4">		
		<?php
					$query_peca = "SELECT id, nome FROM peca WHERE id = $id_peca";
					$result_peca = $conn->prepare($query_peca);
					$result_peca->execute();
					$row_peca = $result_peca->fetch(PDO::FETCH_ASSOC);
					extract($row_peca);
				?>
		<div class="row">
			<div class="col-md-5">
				
			<h2>Operações: <?php echo $nome ?></h2>
			</div>
			<div class="col-md-3 list-icon">
				<a href="<?php echo URL ?>inserir-operacao"><i class="fa fa-clipboard"></i></a>
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
                <th scope="col-3">Código</th>
                <th scope="col-2">Operação</th>
                <th scope="col-2">Descrição</th>
                <th colspan>Ação</th>
              </tr>
            </thead>

            <tbody>
    <?php
    
		// Recuperar os GRUPOS
    $query_reg = "SELECT in_op.id AS id_in_operacao, 
										peca.nome AS peca,
                		tipo_operacao.nome AS operacao,
                		in_op.descricao AS descricao,
                		tipo_operacao.cod_op AS codigo
    FROM inserir_op AS in_op 
    INNER JOIN peca AS peca 
    ON peca.id=in_op.id_peca
    INNER JOIN tipo_operacao AS tipo_operacao 
    ON tipo_operacao.id=in_op.id_operacao WHERE id_peca = $id_peca";
    
    $result_reg = $conn->prepare($query_reg);
    $result_reg->execute();

		if (($result_reg) AND ($result_reg->rowCount() != 0)) {
			while ($row_reg = $result_reg->fetch(PDO::FETCH_ASSOC)) {
				// echo "<pre>";
				// var_dump($row_op);
				// echo "</pre>";
				extract($row_reg)
				?> 

              <tr>
              	<td><?php echo $codigo ?></td>           
                <td><?php echo $operacao ?></td>
                <td><?php echo $descricao ?></td>
                <td class="row">
                	<div class="col-2 action-icon">
                		<a href="<?php echo URL . 'detalhes-in_op?id_in_op=' . $id_in_operacao ?>"><i class="fa fa-eye"></i></a>
                		</div>
                	<div class="col-2 action-icon">
                		<a href="<?php echo URL . 'editar-in_op?id_in_op=' . $id_in_operacao ?>"><i class="fa fa-pen"></i></a>
                		</div>
                	<!-- <div class="col-2 action-icon">
                			<a href="<?php echo URL . 'excluir-item?id_in_op=' . $id . ', nome=' . $nome ?>"><i class="fa fa-trash" onclick=""></i></a>
                		</div> -->
                		<div class="col-2 action-icon">
                			<a onclick="apagarRegistro(<?php echo $id_in_operacao ?>)" href="<?php echo URL . 'excluir-item?id_in_op=' . $id_in_operacao ?>"><i class="fa fa-trash"></i></a>
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
		
	
	<script src="./assets/js/custom.js"></script>

<?php
//Inclui o footer
include_once './include/footer.php';
?>