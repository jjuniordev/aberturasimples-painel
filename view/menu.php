<!DOCTYPE html>
<html lang="pt">

    <?php 
        include('default.php');
        include '../model/funcoes.php';
        //protegePagina(); // Chama a função que protege a página
        $nome_usuario = $_SESSION['usuarioNome'];
        $id_usuario = $_SESSION['usuarioID'];
        $unidade_id = $_SESSION['usuarioUnidade'];
        $permissao = verificarPermissao($id_usuario);

        switch ($permissao) {
          case 4;
          case 5;
            $total_novos = leadsNovos($unidade_id);
                    if ($total_novos != 0) {
                      if ($total_novos > 99) {
                        $icone = '<div><i class="bell icon"></i>99+</div>';
                        $msg = 'Você possui '.$total_novos.' leads novos';
                        $msg .= '<p></p><a href="recebidos.php" class="ui fluid mini button">Ver todos</a>';
                        $qtd_title = "(99+)";
                      } elseif ($total_novos <= 99) {
                        $icone = '<div><i class="bell icon"></i>'.$total_novos.'</div>';
                        $msg = 'Você possui '.$total_novos.' leads novos';
                        $msg .= '<p></p><a href="recebidos.php" class="ui fluid mini button">Ver todos</a>';
                        $qtd_title = "(".$total_novos.")";
                      }
                    } elseif ($total_novos == 0) {
                      $icone = '<i class="bell icon"></i>';
                      $msg = 'Tudo normal por aqui :)';
                      $qtd_title = "";
                    }
            break;
          case 1;
          case 2;
          case 3;
            $total_pendentes = leadsPendentes();
                    if ($total_pendentes != 0) {
                      if ($total_pendentes > 99) {
                        $icone = '<div><i class="bell icon"></i>99+</div>';
                        $msg = 'Você possui '.$total_pendentes.' Leads pendentes';
                        $msg .= '<p></p><a href="pendentes.php" class="ui fluid mini button">Ver todos</a>';
                        $qtd_title = "(99+)";
                      } elseif ($total_pendentes <= 99) {
                        $icone = '<div><i class="bell icon"></i>'.$total_pendentes.'</div>';
                        $msg = 'Você possui '.$total_pendentes.' Leads pendentes';
                        $msg .= '<p></p><a href="pendentes.php" class="ui fluid mini button">Ver todos</a>';
                        $qtd_title = "(".$total_pendentes.")";
                      }
                      } elseif ($total_pendentes == 0) {
                        $icone = '<i class="bell icon"></i>';
                        $msg = 'Tudo normal por aqui :)';
                        $qtd_title = "";
                      }
            break;
        }        

    ?>
    <!-- INFORMAÇÃO DE NOTIFICAÇÃO DE LEAD NO FAVICON -->
      <title>Painel <?php echo $qtd_title; ?> - Abertura Simples</title>

      <style type="text/css">
      body {
        background-color: #FFFFFF;
      }
      .ui.menu .item img.logo {
        margin-right: 1.5em;
      }
      .main.container {
        margin-top: 7em;
      }
      .wireframe {
        margin-top: 2em;
      }
      .ui.footer.segment {
        margin: 5em 0em 0em;
        padding: 5em 0em;
      }
      #atualizar_base_loader {
        display: none;
      }
      </style>



    <body>

    <!-- 
      *****************************************
      ** MENU LATERAL ANTIGO COM SEMANTIC UI **
      *****************************************
      <div class="ui left fixed inverted vertical sticky menu"> -->
      <div class="ui inverted left vertical sidebar menu">
        <br><br><br>
      <div class="item">
        <div class="header">Painel</div>
        <div class="menu">
          <!-- <a class="item">Geral</a> -->
          <!-- <a class="item">Campanha</a> -->
        </div>
      </div>
      <div class="item">
        <div class="header">Leads</div>
        <div class="menu">
          <a class="item">Pendentes</a>
          <a class="item">Geral</a>
        </div>
      </div>
      <div class="item">
        <div class="header">Campanhas</div>
        <div class="menu">
          <a class="item">Geral</a>
          <!-- <a class="item">Leads</a> -->
        </div>
      </div>
      <div class="item">
        <div class="header">Usuários</div>
        <div class="menu">
          <a class="item" href="adduser.php">Adicionar</a>
          <a class="item" href="consultarusuario.php">Gerenciar</a>          
        </div>
      </div>
      <div class="item">
        <div class="header">Suporte</div>
        <div class="menu">
          <a class="item">Central de Ajuda</a>
          <a class="item">Contato</a>
        </div>
      </div>
    </div> 


<style type="text/css">
  /* The sidebar menu */
  .sidenav {
      height: 100%; /* Full-height: remove this if you want "auto" height */
      width: 160px; /* Set the width of the sidebar */
      position: fixed; /* Fixed Sidebar (stay in place on scroll) */
      z-index: 1; /* Stay on top */
      top: 0; /* Stay at the top */
      left: 0;
      background-color: #1b1c1d; /* Black */
      overflow-x: hidden; /* Disable horizontal scroll */
      padding-top: 42px;
  }

  /* The navigation menu links */
  .sidenav a {
      /*padding: 6px 8px 6px 16px;
      text-decoration: none;
      font-size: 25px; */
      /*color: #818181;*/
      /*display: block;*/
  }

  /* When you mouse over the navigation links, change their color */
  .sidenav a:hover {
      /*color: #f1f1f1;*/
  }

  #top-item {
    color: #F1F1F1;
    font-weight: bold;
    margin-left: 12px;
    font-size: 10pt;
  }


  #menu-item {
    margin-left: 16px;
  }

  #menu {
    display: none;
  }

  /* Style page content */
  .main {
      margin-left: 160px; /* Same as the width of the sidebar */
      padding: 0px 10px;
  }

  /* On smaller screens, where height is less than 450px, change the style of the sidebar (less padding and a smaller font size) */
  @media screen and (max-height: 450px) {
      .sidenav {padding-top: 15px;}
      .sidenav a {font-size: 18px;}
  }
</style>
    <?php 
      if ($permissao == 5) {
        echo '<script>
          $(document).ready(function(){
            $("a#menu-item").each(function(){
            var a_href = $(this).attr("name");
            if (a_href == "privilegio" || a_href == "privilegio2") {
              $(this).hide();
            }          
            });
            $("div#top-item").each(function(){
            var div_href = $(this).attr("name");
            if (div_href == "privilegio2" || div_href == "privilegio") {
              $(this).hide();
            }          
            });
            $("div.divider").each(function(){
            var div_div = $(this).attr("name");
            if (div_div == "privilegio2" || div_div == "privilegio") {
              $(this).hide();
            }          
            });
        });
        </script>';
      } elseif ($permissao == 4) {
        echo '<script>
            $(document).ready(function(){
            $("a#menu-item").each(function(){
            var a_href = $(this).attr("name");
            if (a_href == "privilegio2") {
              $(this).hide();
            }          
            });
            $("div#top-item").each(function(){
            var div_href = $(this).attr("name");
            if (div_href == "privilegio2") {
              $(this).hide();
            }          
            });
            $("div.divider").each(function(){
            var div_div = $(this).attr("name");
            if (div_div == "privilegio2") {
              $(this).hide();
            }          
            });
            
        });
        </script>';
      }
     ?>
     <?php 
      if ($permissao <= 3 && $permissao != 1) { 
        # MENU DE COLABORADOR E ADMIN
        include 'menu_admin.php';

      } elseif ($permissao == 1) { 
        # MENU DO ADMIN MASTER
        include 'menu_admin.php';

      } elseif ($permissao == 4) { 
        # MENU DE ASSOCIADO
        include 'menu_associado.php';
        
      } else {
        # MENU DE USUÁRIO COMUM
        include 'menu_usuario.php';
      }

    ?>
    

    <!-- Page content -->
    <div class="main"> 
       
    <div id="menu-superior" class="ui fixed inverted menu">  
      <a class="item" id="menu"><i class="sidebar icon"></i></a>
      <a href="index.php" class="header item">
        <img class="logo" src="../images/logo-pq.png">
       Abertura Simples
      </a>      
        <div class="right menu">
            <?php 
              if ($permissao <= 3) {
                echo '<div class="item">';
                include 'buscaEmail.php';   
                echo '</div>';
              } else {
                echo '<div class="item">';
                include 'buscaEmail_associado.php';   
                echo '</div>';
              }
              
            ?>
          <div class="ui icon dropdown item">
            <?php echo $icone; ?>
            <div class="menu">
              <div class="ui message">
                <div class="header"><i class="exclamation circle icon"></i>Notificações</div>
                <p><?php echo $msg; ?></p>
              </div>
            </div>
          </div>
          <div class="ui icon dropdown item">
            <a class="center floated">
            <i class="user icon">&nbsp;</i>Olá, <?php  echo utf8_encode($nome_usuario); ?> !
            </a>
            <div class="menu">
              <div class="ui message">
                <div class="header"><i class="user circle icon"></i>Minha Conta</div>
                <p>
                  <?php 
                    # CASO USUARIO FOR ASSOCIADO, EXIBE O NOME DA UNIDADE DO MESMO.
                    $level = buscaLevel($id_usuario); 
                    if ($level == "Associado" || utf8_encode($level) == "Usuário") {
                      $unid = getNomeUnidade($id_usuario);
                      echo utf8_encode($level)." (".utf8_encode($unid).")";
                    } else {
                      echo utf8_encode($level);   
                    }
                    
                  ?>
                </p>
                <p></p>                
                <a href="gerenciar.php" class="ui fluid mini button">Gerenciar</a>
                <?php 
                  if ($permissao == 1) {
                    echo '<br><a href="upload_campanhas.php" name="botao_atualizar" class="ui fluid mini button">Upload</a>';
                  }
                 ?>
              </div>
            </div>
          </div>
          
          <a class="right floated icon item" href="logout.php"><i class="sign out alternate outline icon"></i>&nbsp;Sair</a>

        </div>
    </div>
    <script type="text/javascript">
      //$('.ui.sidebar').sidebar('toggle');
      $('#menu').click(function(){
        $('.ui.sidebar').sidebar('setting', 'transition', 'overlay');
        $('.ui.sidebar').sidebar('toggle');

      });
    </script>

    <script type="text/javascript">
        $(window).on('resize', function(){
        var win = $(this); //this = window
        if (win.width() <= 900) { 
          $('.sidenav').hide();
          $('#menu').show();
        } else if (win.width() > 900) {
          $('.sidenav').show();
          $('#menu').hide();
        }
      });
    </script>
    </body>
</html>
<br><br><br><br>

<script type="text/javascript">
  $('.ui.dropdown')
  .dropdown()
;
</script>

<!-- BEGIN JIVOSITE CODE {literal} -->
<script type='text/javascript'>
//   (function(){ var widget_id = 'VfkM5dKuzQ';var d=document;var w=window;function l(){
//     var s = document.createElement('script'); 
//     s.type = 'text/javascript'; 
//     s.async = true; 
//     s.src = '//code.jivosite.com/script/widget/'+widget_id; 
//     var ss = document.getElementsByTagName('script')[0]; 
//     ss.parentNode.insertBefore(s, ss);
//   }
//   if(d.readyState=='complete'){
//     l();
//   } else {
//     if(w.attachEvent){
//       w.attachEvent('onload',l);
//     } else { 
//       w.addEventListener('load',l,false);
//     }
//   }
// })();
</script>
<!-- {/literal} END JIVOSITE CODE -->
<div class="ui active dimmer" id="atualizar_base_loader">
    <div class="ui text loader">Atualizando dados</div>
  </div>
  <p></p>

<script type="text/javascript">
  $('[name="botao_atualizar"]').click(function(){
    $('#atualizar_base_loader').show();
  });
</script>