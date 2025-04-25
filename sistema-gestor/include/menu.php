<?php

include_once ('conexao/conexao.php');

//ABRIR ESSA PÁGINA QUANDO LOGADO
if ((!isset($_SESSION['id'])) AND (!isset($_SESSION['nome']))) {
   $_SESSION['msg'] = '<div class="alert alert-danger" role="alert">É necessário realizar o Login, para acessar o sistema!</div>';  
   header("Location: login.php");
}
?>
<!-- CÓDIGO AO CLICAR NO LINK E FECHA NOS DISPOSITIVOS MÓVEIS  -->
            <script type="text/javascript">
                document.addEventListener("DOMContentLoaded", function(){
                   var links = document.querySelectorAll(".navbar-nav li a:not([href='#'])");
                   for(var x=0; x<links.length; x++){
                      links[x].onclick = function(){
                         document.querySelector("button.navbar-toggler").click();
                      }
                   }
                });
            </script>
            <!-- FIM  -->


            <!-- STYLE PERFIL USUÁRIO-->

               <style>
                  
                  span{
                     color: white;
                     padding-left: 10px;
                  }
                  .menu-perfil img{
                     width: 30px;
                     height: 30px;
                     border-radius: 50%;
                  }
                  .menu-perfil{
                     margin-left: 300px;
                  }

                  @media screen and (max-width: 480px){

                  .menu-perfil{
                     margin-left: 0;
                  }
                  }
                  @media screen and (min-width: 481px) and (max-width: 768px){
                  .menu-perfil{
                     margin-left: 0;
                  }     
                  }
               </style>

            <!-- FIM STYLE PERFIL USUÁRIO-->

            <!-- NAVEGADOR ------------------------------------>

            <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
             
              <div class="container">
                <a class="navbar-brand" href="#"></a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExample07" aria-controls="navbarsExample07" aria-expanded="false" aria-label="Toggle navigation">
                  <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse menu" id="navbarsExample07">
                  <ul class="navbar-nav mr-auto">
                  	<li class="nav-item menu-hover">
                      <a class="nav-link" href="<?php echo URL ?>home" data-placement="bottom" title="Início"><i class="fa fa-home">&nbsp;&nbsp;<strong class="menu">Home</strong></i></a>
                    </li>
                    <li class="nav-item">
                    <li class="nav-item menu-hover">
                      <a class="nav-link" href="<?php echo URL ?>cadastrar" data-placement="bottom" title="Cadastrar"><i class="fa fa-clipboard">&nbsp;&nbsp;<strong class="menu">Cadastros</strong></i></a>
                    </li>
                    <li class="nav-item menu-hover">
                      <a class="nav-link" href="<?php echo URL ?>consultar" data-placement="bottom" title="Consultar"><i class="fa fa-search">&nbsp;&nbsp;<strong class="menu">Consultar</strong></i></a>
                    </li>
                    <!-- <li class="nav-item">
                      <a class="nav-link" href="<?php echo URL ?>engenharia">Engenharia</a>                      
                    </li> -->

                    <li class="nav-item menu-hover">
                      <a class="nav-link" href="<?php echo URL ?>sair" data-placement="bottom" title="Sair do Sistema"><i class="fa fa-power-off">&nbsp;&nbsp;<strong class="menu">Sair</strong></i></a>
                    </li>
                   
                      <li class="nav-item menu-perfil">
                         <a class="nav-link" href="perfil"><span><?php echo $_SESSION['nome']; ?></span><img src="assets/images/<?php echo $_SESSION['imagem'] ?>">
                        </a>
                       </li>
                            
                  </ul>               


                  
                </div>
              </div>
            </nav>

            <!-- FIM NAVEGADOR --------------------------->