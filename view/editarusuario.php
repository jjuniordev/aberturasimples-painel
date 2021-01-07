<?php include('menu.php'); // ----- CARREGA O MENU ----- // ?> 

<?php include('default.php');  // ----- CARREGA A DEFAULT----- // ?>

<?php
require_once('../controller/ControleUsuario.php'); // ----- CARREGA O CONTROLE ----- //
Processo('editar'); // ----- PASSA O PROCESSO AO CONTROLE ----- //
?>

<script src="js/Validacaoform.js"></script>

<div class="container">

    <form class="form-signin" action="" id="form" name="form" method="post">
        <h2 class="form-signin-heading"></h2>

          <?php while ($row = mysqli_fetch_array($rs)) { ?>
        
        <div class="form-group">
            <input type="text" id="nome" name="nome" class="form-control" value="<?php echo $row['nome']; ?>">
            <input type="email" id="email" name="email" class="form-control" value="<?php echo $row['email']; ?>">
            <input type="text" id="senha" name="senha" class="form-control" value="<?php echo $row['senha']; ?>">
        </div>

   <?php } ?>
        
        <div class="form-group">
            <div>
                <input type="button" name="button" id="button" value="Editar" class="btn btn-primary" onclick="validar(document.form);"/>
                <input type="hidden" name="ok" id="ok" />
            </div>
        </div>

    </form>

</div> <!-- /container -->

