<?php 

    include 'menu.php';
    $id_user = $_SESSION['usuarioID'];
    $permissao  = verificarPermissao($id_user); 
    # Verificar permissão e negar acesso caso não tenha privilégios
    if ($permissao >= 4) {
        echo "Você não tem permissão para acessar esta página, <a href='index.php'>clique aqui</a> para voltar ao painel.";
        exit();
    }
?>

<div id="ui grid">
    <div class="ui container">
        <h1 class="ui center aligned header"><i class="upload icon"></i>Upload</h1>
        <div class="ui inverted menu" id="submenu">
            <a id="google" class="item" href="upload_campanhas.php">
                Google
              </a>
            <a id="facebook" class="active item" href="upload_campanhas_face.php">
                Facebook
              </a>
            </div>
            <form class="ui form" action="insert_campanhas_face.php" method="post" name="upload_excel" enctype="multipart/form-data">
                <fieldset>

                    <!-- Form Name -->
                    <legend>Facebook</legend>

                    <!-- File Button -->
                    <div class="">
                        <label for="filebutton">Selecionar Arquivo</label>
                        <div class="">
                            <input type="file" name="file" id="file" class="">
                        </div>
                    </div>

                    <!-- Button -->
                    <div class="">
                        <label class="" for="singlebutton">Importação de arquivo</label>
                        <div class="">
                            <button type="submit" id="submit" name="Import" class="" data-loading-text="Loading...">Importar</button>
                        </div>
                    </div>

                </fieldset>
            </form>
        <?php
           //get_all_records();
        ?>  
    </div>
</div>

