<?php 

	include('menu.php'); 
	$id_user = $_SESSION['usuarioID'];
	$unidade_id = $_SESSION['usuarioUnidade'];
	$permissao = verificarPermissao($id_user);
  	$responsaveis 	= @implode(",", $_SESSION['responsaveis']);

	if ($permissao == 5) {
		#QUERY PARA BUSCAR OS DADOS DA TABELA DE LEADS NOVOS.
		  $query = mysql_query(
		        "SELECT 
					a.id
					,b.data_conversao
					,date_format(b.data_conversao,'%d/%m/%y - %H:%i') as data_limpa
					,a.nome
					,a.email
					,a.telefone
					,a.cidade
					,a.estado
					,b.mensagem
					,c.status2
					,d.nome_unidade
					,a.esta_ativo
		            ,e.nome as responsavel
		            ,e.sobrenome as responsavel_n
                    ,b.identificador
                    ,b.origem
                    ,b.campaign
                    ,b.midia
                    ,b.nicho_empresa
                    ,f.faturamento
                    ,t.tipo_empresa
				FROM tb_leads a
					INNER JOIN tb_conversoes b ON a.id_ultima_conversao = b.id
					INNER JOIN tb_lead_status c ON a.id_status = c.id
					INNER JOIN tb_unidades d ON a.id_unidade = d.id
		            INNER JOIN tb_usuarios e ON a.id_usuario = e.id
                    LEFT JOIN tb_chatbot_faturamento f ON b.id_faturamento_empresa = f.id
                    LEFT JOIN tb_chatbot_tipoempresa t ON b.id_tipo_empresa = t.id
				WHERE a.esta_ativo = 1
					AND c.status2 != 'Nulo'
					AND c.id = 5
					AND a.id_unidade = $unidade_id
					AND e.id in ($id_user)
				ORDER BY data_conversao DESC;
		        ");
	} else {
		if ($responsaveis != "") {
			$query = mysql_query(
		        "SELECT 
					a.id
					,b.data_conversao
					,date_format(b.data_conversao,'%d/%m/%y - %H:%i') as data_limpa
					,a.nome
					,a.email
					,a.telefone
					,a.cidade
					,a.estado
					,b.mensagem
					,c.status2
					,d.nome_unidade
					,a.esta_ativo
		            ,e.nome as responsavel
		            ,e.sobrenome as responsavel_n
                    ,b.identificador
                    ,b.origem
                    ,b.campaign
                    ,b.midia
                    ,b.nicho_empresa
                    ,f.faturamento
                    ,t.tipo_empresa
				FROM tb_leads a
					INNER JOIN tb_conversoes b ON a.id_ultima_conversao = b.id
					INNER JOIN tb_lead_status c ON a.id_status = c.id
					INNER JOIN tb_unidades d ON a.id_unidade = d.id
		            INNER JOIN tb_usuarios e ON a.id_usuario = e.id
                    LEFT JOIN tb_chatbot_faturamento f ON b.id_faturamento_empresa = f.id
                    LEFT JOIN tb_chatbot_tipoempresa t ON b.id_tipo_empresa = t.id
				WHERE a.esta_ativo = 1
					AND c.status2 != 'Nulo'
					AND c.id = 5
					AND a.id_unidade = $unidade_id
					AND e.id in ($responsaveis)
				ORDER BY data_conversao DESC;
		        ");
		} else {
			$query = mysql_query(
		        "SELECT 
					a.id
					,b.data_conversao
					,date_format(b.data_conversao,'%d/%m/%y - %H:%i') as data_limpa
					,a.nome
					,a.email
					,a.telefone
					,a.cidade
					,a.estado
					,b.mensagem
					,c.status2
					,d.nome_unidade
					,a.esta_ativo
		            ,e.nome as responsavel
		            ,e.sobrenome as responsavel_n
                    ,b.identificador
                    ,b.origem
                    ,b.campaign
                    ,b.midia
                    ,b.nicho_empresa
                    ,f.faturamento
                    ,t.tipo_empresa
				FROM tb_leads a
					INNER JOIN tb_conversoes b ON a.id_ultima_conversao = b.id
					INNER JOIN tb_lead_status c ON a.id_status = c.id
					INNER JOIN tb_unidades d ON a.id_unidade = d.id
		            INNER JOIN tb_usuarios e ON a.id_usuario = e.id
                    LEFT JOIN tb_chatbot_faturamento f ON b.id_faturamento_empresa = f.id
                    LEFT JOIN tb_chatbot_tipoempresa t ON b.id_tipo_empresa = t.id
				WHERE a.esta_ativo = 1
					AND c.status2 != 'Nulo'
					AND c.id = 5
					AND a.id_unidade = $unidade_id
				ORDER BY data_conversao DESC;
		        ");
		}
	}  

  include 'montarPagina_associados.php';

?>

