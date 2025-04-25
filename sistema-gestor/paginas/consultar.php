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
<title>Consultar</title>
</head>
<?php



//Inclui o menu
include_once './include/menu.php';
 //echo "Página cadastro.";
?>
<div class="container titulo-form">
	<h2>Consultar</h2>
<section>
	<div class="row">
  <div class="col-sm-3">
    <div class="card">
      <div class="card-body top-card">
        <div class="icon-home">          
          <i class="fa fa-object-group"></i>
        </div>
          <a href="<?php echo URL ?>listar-grupos" class="btn btn-primary bottom-card"><div class="titulo-botao"><strong>Grupos</strong></div></a>
      </div>
    </div>
  </div>
  <div class="col-sm-3">
    <div class="card">
      <div class="card-body top-card">
        <!-- <h5 class="card-title">Operação</h5> -->
        <!-- <p class="card-text">Cadastrar Operação.</p> -->
        <div class="icon-home">
          <i class="fa fa-sitemap"></i>
        </div>
          <a href="<?php echo URL ?>listar-operacao" class="btn btn-primary bottom-card"><div class="titulo-botao"><strong>Operação</strong></div></a>
      </div>
    </div>
  </div>
  <div class="col-sm-3">
    <div class="card">
      <div class="card-body top-card">
        <!-- <h5 class="card-title">Operação</h5> -->
        <!-- <p class="card-text">Cadastrar Operação.</p> -->
        <div class="icon-home">
          <i class="fa fa-cog"></i>
        </div>
          <a href="<?php echo URL ?>listar-peca" class="btn btn-primary bottom-card"><div class="titulo-botao"><strong>Peça</strong></div></a>
      </div>
    </div>
  </div>
  <div class="col-sm-3">
    <div class="card">
      <div class="card-body top-card">
        <div class="icon-home">
          <img src="assets/images/icons/icon-material.png">
        </div>
          <a href="<?php echo URL ?>listar-material" class="btn btn-primary bottom-card"><div class="titulo-botao"><strong>Materiais</strong></div></a>
      </div>
    </div>
  </div>
  
</section>
<br>
<section>
  <div class="row">
    <div class="col-sm-3">
    <div class="card">
      <div class="card-body top-card">
        <div class="icon-home">
          <i class="fa fa-file"></i>
        </div>
          <a href="<?php echo URL ?>listar-ordens" class="btn btn-primary bottom-card"><div class="titulo-botao"><strong>Ordem Produção</strong></div></a>
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
          <a href="<?php echo URL ?>consultar" class="btn btn-primary bottom-card"><div class="titulo-botao"><strong>Consultar</strong></div></a>
      </div>
    </div>
  </div>
    
  </div>
</section>

</div>
<?php

//Inclui o footer
include_once './include/footer.php';
?>