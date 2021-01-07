<?php include('menu.php'); // ----- CARREGA O MENU ----- // ?> 

<?php include('default.php');  // ----- CARREGA A DEFAULT----- // ?>

<?php
require_once('../controller/ControleUsuario.php'); // ----- CARREGA O CONTROLE ----- //
Processo('incluir'); // ----- PASSA O PROCESSO AO CONTROLE ----- //
?>

<script src="js/Validacaoform.js"></script>

<div class="ui container">
    <form action="" id="form" name="form" method="post">
        <h2 class="ui icon center aligned header">
            <i class="pencil alternate icon"></i>
            Cadastrar
        </h2>

        <div class="ui form">
            <div class="field">
                <label>Nome</label>
                <input type="text" id="nome" name="nome" placeholder="Nome" required autofocus>
            </div>
            <div class="field">
                <label>Sobrenome</label>
                <input type="text" id="sobrenome" name="sobrenome" class="form-control" placeholder="Sobrenome" required>
            </div>
            <div class="field">
                <label>E-mail</label>
                <input type="email" id="email" name="email" class="form-control" placeholder="Email" required>
            </div>
            <div class="field">
                <label>Login</label>
                <input type="text" id="login" name="login" class="form-control" placeholder="Login" required>
            </div>
            <div class="field">
                <label>Senha</label>
                <input type="password" id="senha" name="senha" class="form-control" placeholder="Senha" required>
            </div>
            <div class="field">
                <label>Permissão</label>

                <input type="number" id="level" name="id_level" class="form-control" placeholder="Level" required>    
            </div>
            <div class="field">
                <label>Google Conta</label>
                <input type="text" id="account_name" name="account_name" class="form-control" placeholder="Account" required>    
            </div>
            <div class="field">
                <label>Google Conta ID</label>
                <input type="number" id="account_id" name="account_id" class="form-control" placeholder="Account ID" required>
            </div>
        </div>
        <br>
        <div class="ui container">
                <input type="button" name="button" id="button" value="Cadastrar" class="ui green button" onclick="validar(document.form);"/>
                <input type="hidden" name="ok" id="ok" />
            </div>
        </div>
    </form>
    </pre>

</div> <!-- /container -->

