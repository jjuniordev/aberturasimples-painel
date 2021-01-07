 <?php 

	include 'seguranca.php';
	include 'funcoes.php';
	date_default_timezone_set('America/Sao_Paulo');        
	$data_atual = date('Y-m-d H:i');
	$date_reduz = date('Y-m-d');
	$nome 		= utf8_decode($_POST['nome']);
	$email		= $_POST['email'];
	$telefone 	= $_POST['telefone'];
	$cidade 	= utf8_decode($_POST['cidade']);
	$estado 	= $_POST['estado'];
	$identificador = utf8_decode($_POST['identificador']);
	$faturamento 	= utf8_decode($_POST['faturamento']);
	$nicho 			= utf8_decode($_POST['nicho']);
	$tipo_empresa 	= utf8_decode($_POST['tipo_empresa']);
	$msg		= utf8_decode($_POST['msg']);
	$unidade	= $_POST['unidade'];
	$chave 		= $nome.$email.$cidade.$estado;
	$private 	= md5($email);
	
	# Verifica se já existe o email cadastrado e retorna o ID, caso contrário retorna 0.
	function uniqueLeads($email) {
        $query = mysql_query("SELECT id FROM tb_leads WHERE email = '".$email."'");
        if (mysql_num_rows($query) == 1) {
            $retorno = mysql_result($query, 0);
        } else {
            $retorno = 0;
        }
        return $retorno;
    }

    function cadastrarConversao($data_atual,$identificador,$msg,$id_lead,$unique_hash,$nome,$telefone,$cidade,$estado,$faturamento,$nicho,$tipo_empresa) {
    	$inserir_conversao = "INSERT INTO `integrations`.`tb_conversoes`
	        (`id`,
	        `data_conversao`,
	        `identificador`,
	        `origem`,
	        `mensagem`,
	        `id_lead`,
	        `unique_hash`,
	        `nome_conversao`,
	        `telefone_conversao`,
	        `cidade_conversao`,
	        `estado_conversao`,
	        `id_faturamento_empresa`,
	        `nicho_empresa`,
	        `id_tipo_empresa`
	        ) VALUES (
	        null,
	        '".$data_atual."',
	        '".$identificador."',
	        'Manual',
	        '".$msg."',
	        ".$id_lead.",
	        '".$unique_hash."',
	        '".$nome."',
	        '".$telefone."',
	        '".$cidade."',
	        '".$estado."',
	        '".$faturamento."',
	        '".$nicho."',
	        '".$tipo_empresa."'
	        )";

	    mysql_query($inserir_conversao);

	    return mysql_insert_id();
    }

    $is_new = uniqueLeads(strtolower($email));

    # Se o e-mail não existir na base cadastra um novo e cadastra uma conversão
    if ($is_new == 0) {
    	
    	mysql_query("INSERT tb_leads SELECT null,'".$nome."','".$email."','".$telefone."','".$cidade."','".$estado."',".$unidade.",".$_SESSION['usuarioID'].",2,1,'".$private."',0");

		$id_lead = mysql_insert_id(); # Retorna o ID do Insert executado anteriormente

		$unique_hash = md5($date_reduz.$identificador.$id_lead); # Criando a hash da tabela de conversões 

		$id_ultima_conversao = cadastrarConversao(	$data_atual,
													$identificador,
													$msg,
													$id_lead,
													$unique_hash,
													$nome,
													$telefone,
													$cidade,
													$estado,
													$faturamento,
													$nicho,
													$tipo_empresa
													); # Função que cadastra conversão, retorna o ID do cadastro

	    mysql_query("UPDATE tb_leads SET id_ultima_conversao = ".$id_ultima_conversao." WHERE id = ". $id_lead); # Insere o cadastro da conversão na tabela de Leads

    } else {
    	
    	$id_lead = $is_new; # Recebe o ID do Lead cadastrado
	    $unique_hash = md5($date_reduz.$identificador.$id_lead); # Criando a hash da tabela de conversões 

	    $id_ultima_conversao = cadastrarConversao(	$data_atual,
													$identificador,
													$msg,
													$id_lead,
													$unique_hash,
													$nome,
													$telefone,
													$cidade,
													$estado,
													$faturamento,
													$nicho,
													$tipo_empresa
													); # Função que cadastra conversão, retorna o ID do cadastro
		
		# Altera os dados do Lead de acordo com a última conversão feita 
	    $update_lead = "UPDATE `integrations`.`tb_leads` SET
        `nome` = '".ucwords(strtolower($nome))."',
        `telefone` = '".$telefone."',
        `cidade` = '".$cidade."',
        `estado` = '".$estado."',
        `id_ultima_conversao` = ".$id_ultima_conversao."
        WHERE id = ".$id_lead;
    
    	mysql_query($update_lead);
    }

    # Gerando os logs
	$mensagem = utf8_decode("Novo Lead cadastrado - Nome do lead: ".$nome." [Usuário: ".$_SESSION['usuarioNome']."]");
    salvaLog($mensagem);

    # Cadastrando na lista de atividades do Lead 
    adicionarAtividade(1,"Nova conversão realizada!",$private,$_SESSION['usuarioID']); 

    

?>