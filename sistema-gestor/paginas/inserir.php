<?php

session_start();

include_once ('conexao/conexao.php');

//ABRIR ESSA PÁGINA QUANDO LOGADO
if ((!isset($_SESSION['id'])) AND (!isset($_SESSION['nome']))) {
	$_SESSION['msg'] = '<div class="alert alert-danger" role="alert">É necessário realizar o Login, para acessar o sistema!</div>';	
	header("Location: login.php");
}

//Inclui o head
include_once './include/head.php';
?>
<title>Cadastrar</title>
</head>
<?php



//Inclui o menu
include_once './include/menu.php';
 //echo "Página cadastro.";
?>
<div class="container titulo-form">
	<h2>Inserir</h2>
<section>
	<div class="row">
  <div class="col-sm-3">
    <div class="card">
      <div class="card-body top-card">
        <div class="icon-home">
          <i class="fa fa-sitemap"></i>
        </div>
          <a href="<?php echo URL ?>inserir-operacao" class="btn btn-primary bottom-card"><div class="titulo-botao"><strong>Operação</strong></div></a>
      </div>
    </div>
  </div>
  <div class="col-sm-3">
    <div class="card">
      <div class="card-body top-card">
        <!-- <h5 class="card-title">Operação</h5> -->
        <!-- <p class="card-text">Cadastrar Operação.</p> -->
        <div class="icon-home">
          <i class="fa fa-industry"></i>
        </div>
          <a href="<?php echo URL ?>inserir-ordem" class="btn btn-primary bottom-card"><div class="titulo-botao"><strong>Ordem de Fabricação</strong></div></a>
      </div>
    </div>
  </div>
  <div class="col-sm-3">
    <div class="card">
      <div class="card-body top-card">
        <!-- <h5 class="card-title">Operação</h5> -->
        <!-- <p class="card-text">Cadastrar Operação.</p> -->
        <div class="icon-home">
          <i class="fa fa-search"></i>
        </div>
          <a href="<?php echo URL ?>Consultar" class="btn btn-primary bottom-card"><div class="titulo-botao"><strong>Consultar</strong></div></a>
      </div>
    </div>
  </div>
</section>


</div>
<?php

//Inclui o footer
include_once './include/footer.php';
?>