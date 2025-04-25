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
<title>Home</title>
</head>
<?php



//Inclui o menu
include_once './include/menu.php';
 //echo "Página cadastro.";
?>
<div class="container">
	<h2>Página Home</h2>
<section>
	<div class="row">
  <div class="col-sm-3">
    <div class="card">
      <div class="card-body top-card">
        <div class="icon-home">
          <i class="fa fa-clipboard"></i>
        </div>
          <a href="<?php echo URL ?>cadastrar" class="btn btn-primary bottom-card"><div class="titulo-botao"><strong>Cadastrar</strong></div></a>
      </div>
    </div>
  </div>
  <div class="col-sm-3">
    <div class="card">
      <div class="card-body top-card">
        <div class="icon-home">
          <i class="fa fa-edit"></i>
        </div>
          <a href="<?php echo URL ?>inserir" class="btn btn-primary bottom-card"><div class="titulo-botao"><strong>Inserir</strong></div></a>
      </div>
    </div>
  </div>
  <div class="col-sm-3">
    <div class="card">
      <div class="card-body top-card">
        <!-- <h5 class="card-title">Operação</h5> -->
        <!-- <p class="card-text">Cadastrar Operação.</p> -->
        <div class="icon-home">
          <i class="fa fa-clipboard-list"></i>
        </div>
          <a href="<?php echo URL ?>listar-ordens" class="btn btn-primary bottom-card"><div class="titulo-botao"><strong>Ordens de Produção</strong></div></a>
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
<br>
<section>
  <div class="row">
  <div class="col-sm-3">
    <div class="card">
      <div class="card-body top-card">
        <div class="icon-home">
          <i class="fas fa-drafting-compass"></i>
        </div>
          <a href="<?php echo URL ?>engenharia" class="btn btn-primary bottom-card"><div class="titulo-botao"><strong>Engenharia</strong></div></a>
      </div>
    </div>
  </div>
  <div class="col-sm-3">
    <div class="card">
      <div class="card-body top-card">
        <div class="icon-home">
          <i class="fas fa-hard-hat"></i>
        </div>
          <a href="<?php echo URL ?>producao" class="btn btn-primary bottom-card"><div class="titulo-botao"><strong>Produção</strong></div></a>
      </div>
    </div>
  </div>
  <div class="col-sm-3">
    <div class="card">
      <div class="card-body top-card">
        <!-- <h5 class="card-title">Operação</h5> -->
        <!-- <p class="card-text">Cadastrar Operação.</p> -->
        <div class="icon-home">
          <i class="fa fa-file-pdf" aria-hidden="true"></i>
        </div>
          <a href="<?php echo URL ?>cadastrar-peca" class="btn btn-primary bottom-card"><div class="titulo-botao"><strong>Relatórios</strong></div></a>
      </div>
    </div>
  </div>
  <div class="col-sm-3">
    <div class="card">
      <div class="card-body top-card">
        <!-- <h5 class="card-title">Operação</h5> -->
        <!-- <p class="card-text">Cadastrar Operação.</p> -->
        <div class="icon-home">
          <i class="fa fa-list" aria-hidden="true"></i>
        </div>
          <a href="<?php echo URL ?>dashboard" class="btn btn-primary bottom-card"><div class="titulo-botao"><strong>Estoque</strong></div></a>
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