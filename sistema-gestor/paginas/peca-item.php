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
<title>Itens por Peça</title>
</head>
<?php

//Inclui o menu
include_once './include/menu.php';
 //echo "Página cadastro.";

?>
<div class="container py-4">
	<header class="pb-3 mb-4">		
		
		<div class="row">
			<div class="col-md-5">
				<?php
					$query_peca = "SELECT id, nome FROM peca WHERE id = $id_peca";
					$result_peca = $conn->prepare($query_peca);
					$result_peca->execute();
					$row_peca = $result_peca->fetch(PDO::FETCH_ASSOC);
					extract($row_peca);
				?>
				
			<h2><?php echo mb_strimwidth($nome, 0, 23, "..."); ?></h2>
			</div>
			<div class="col-md-3 list-icon">
				<a href="<?php echo URL ?>cadastrar-itens" data-placement="bottom" title="Cadastrar Itens"><i class="fa fa-clipboard"></i></a>
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
                <th scope="col-3">Nome</th>
                <th scope="col-2">Descricao</th>
                <th scope="col-2">Qtd item</th>
                <th colspan>Ação</th>
              </tr>
            </thead>

            <tbody>
    <?php
    
    $query_reg = "SELECT id, id_peca, nome, descricao, qtd_itens FROM itens WHERE id_peca =" . $id_peca;
		$result_reg = $conn->prepare($query_reg);
		$result_reg->execute();

		if (($result_reg) AND ($result_reg->rowCount() != 0)) {
			while ($row_reg = $result_reg->fetch(PDO::FETCH_ASSOC)) {
				// echo "<pre>";
				// var_dump($row_reg);
				// echo "</pre>";
				extract($row_reg)
				?> 

              <tr>                
                <td><?php echo $nome ?></td>
                <td><?php echo $descricao ?></td>
                <td><?php echo $qtd_itens ?></td>
                <td class="row">
                	<div class="col-2 action-icon">
                		<a href="<?php echo URL . 'peca-operacao?id_peca=' . $id . 'nome=' . $nome ?> "  data-placement="bottom" title="Consultar Operação"><i class="fa fa-sitemap"></i></a>
                		</div>
                	<div class="col-2 action-icon">
                		<a href="<?php echo URL . 'listar-peca?id_peca=' . $id ?>"><i class="fa fa-pen"></i></a>
                		</div>
                	<!-- <div class="col-2 action-icon">
                			<a href="<?php echo URL . 'excluir-peca?id_peca=' . $id . ', nome=' . $nome ?>"><i class="fa fa-trash" onclick=""></i></a>
                		</div> -->
                		<div class="col-2 action-icon">
                			<a onclick="apagarRegistro(<?php echo $id ?>)" href="<?php echo URL . 'excluir-peca?id_peca=' . $id ?>"><i class="fa fa-trash"></i></a>
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