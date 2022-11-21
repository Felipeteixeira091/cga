<?php

$txtTitulo = filter_input_array(INPUT_POST, FILTER_DEFAULT);

include_once "./conf/conexao2.php";
include_once "./json_encode.php";
require_once '../lib/PHPMailer/PHPMailerAutoload.php';

session_start();
$re_sessao = $_SESSION["re"];
$uf_sessao = $_SESSION["uf"];

$acao = "detalhe_Estado";//$txtTitulo['acao'];
$data = date('Y-m-d');
$hora = date('H:i');

$erro = "0";
$msg = "";


if ($acao === "dados") {

    dados($mysqli, $txtTitulo['re']);
} else
if ($acao === "dadosCartao") {

    dadosCartao($mysqli,  $txtTitulo['re'], $re_sessao);
} else
if ($acao === "dadosFrota") {

    dadosFrota($mysqli,  $txtTitulo['re'], $re_sessao);
} else
if ($acao === "cartaoAltera") {

    $re = $txtTitulo['re'];
    $cartaoAtual = $txtTitulo['cartaoAtual'];
    $cartaoNovo = $txtTitulo['cartaoNovo'];
    $cartaoMotivo = $txtTitulo['cartaoMotivo'];

    cartaoAltera($mysqli, $re_sessao, $re, $cartaoAtual, $cartaoNovo, $cartaoMotivo, $data, $hora);
} else
if ($acao === "cartaoAtribui") {
    $re = $txtTitulo['re'];
    $cartaoAtual = $txtTitulo['cartaoAtual'];
    $cartaoNovo = $txtTitulo['cartaoNovo'];

    cartaoAtribui($mysqli, $re_sessao, $re, $cartaoAtual, $cartaoNovo, $data, $hora);
} else
if ($acao === "cartaoDesbloqueio") {
    $re = $txtTitulo['re'];
    $cartaoAtual = $txtTitulo['cartaoAtual'];

    cartaoDesbloqueio($mysqli, $re_sessao, $re, $cartaoAtual, $data, $hora);
} else
if ($acao === "cartaoRemove") {
    $re = $txtTitulo['re'];
    $cartaoAtual = $txtTitulo['cartaoAtual'];

    cartaoRemove($mysqli, $re_sessao, $re, $cartaoAtual, $data, $hora);
} else
if ($acao === "frotaAlteraKm") {

    $re = $txtTitulo['re'];
    $frota = $txtTitulo['frota'];
    $kmAtual = $txtTitulo['kmAtual'];
    $kmNovo = $txtTitulo['kmNovo'];

    frotaAlteraKm($mysqli, $re_sessao, $re, $frota, $kmAtual, $kmNovo, $data, $hora);
} else
if ($acao === "frotaRemove") {
    $re = $txtTitulo['re'];
    $frotaAtual = $txtTitulo['frotaAtual'];

    frotaRemove($mysqli, $re_sessao, $re, $frotaAtual, $data, $hora);
} else
 if ($acao === "cartaoLista") {

    cartaoLista($mysqli);
} else
if ($acao === "veiculoLista") {

    veiculoLista($mysqli);
} else
if ($acao === "FrotaProcura") {

    $txt = $txtTitulo['txt'];
    $atual = $txtTitulo['frotaAtual'];
    frotaProcura($mysqli, $txt, $atual);
} else
if ($acao === "frotaSeleciona") {

    $frota = $txtTitulo['frota'];
    frotaSeleciona($mysqli, $frota);
} else
if ($acao === "frotaAtribui") {

    $re = $txtTitulo['re'];
    $atual = $txtTitulo['atual'];
    $novo = $txtTitulo['novo'];

    frotaAtribui($mysqli, $re_sessao, $re, $atual, $novo, $data, $hora);
} else
if ($acao === "cadastroFrota") {

    $re = $txtTitulo['re'];
    $frotaAtual = $txtTitulo['frotaAtual'];
    $frotaNova = str_replace(" ", "", str_replace("-", "", $txtTitulo['frotaNova']));
    $veiculo = $txtTitulo['veiculo'];
    $km = $txtTitulo['km'];

    cadastroFrota($mysqli, $re_sessao, $re, $frotaAtual, $frotaNova, $veiculo, $km, $data, $hora);
} else
if ($acao === "detalhe_Cargo") {

    cargoLista($mysqli);
} else
if ($acao === "detalhe_Estado") {

    estadoLista($mysqli, $re_sessao);
} else
if ($acao === "detalhe_CN") {

    cnLista($mysqli, $uf_sessao, $re_sessao);
} else
if ($acao === "detalhe_Coordenador") {

    coordenadorLista($mysqli);
} else
if ($acao === "detalhe_Acesso") {

    sistemaLista();
}
if ($acao === "detalhe_Ativo") {

    ativoLista();
} else
if ($acao === "cnLista") {

    cnLista($mysqli, $uf_sessao, $re_sessao);
} else
if ($acao === "coordenadorLista") {

    coordenadorLista($mysqli);
} else
if ($acao === "cargoLista") {

    cargoLista($mysqli);
} else
if ($acao === "estadoLista") {

    estadoLista($mysqli,$re_sessao);
} else 
if ($acao === "cadastroUSUARIO") {

    $re = $txtTitulo['re'];
    $nome = str_replace("0", "O", strtoupper($txtTitulo['nome']));
    $estado = $txtTitulo['estado'];
    $combustivel = $txtTitulo['combustivel'];
    $cartao = $txtTitulo['cartao'];
    $email = $txtTitulo['email'];
    $telefone = $txtTitulo['telefone'];
    $cargo = $txtTitulo['cargo'];
    $coordenador = $txtTitulo['coordenador'];
    $cn = $txtTitulo['cn'];
    $acesso = $txtTitulo['acesso'];

    cadastroUSUARIO($mysqli, $re_sessao, $re, $nome, $estado, $email, $telefone, $cargo, $coordenador, $cn, $acesso, $combustivel, $cartao, $data, $hora);
} else
if ($acao === "editaUSUARIO") {

    $re = $txtTitulo['re'];
    $nome = str_replace("0", "O", strtoupper($txtTitulo['nome']));
    $estado = $txtTitulo['estado'];
    $email = $txtTitulo['email'];
    $telefone = $txtTitulo['telefone'];
    $cargo = $txtTitulo['cargo'];
    $supervisor = $txtTitulo['coordenador'];
    $cn = $txtTitulo['cn'];
    $acesso = $txtTitulo['acesso'];
    $ativo = $txtTitulo['ativo'];
    $senha = $txtTitulo['senha'];

    editaUSUARIO($mysqli, $re_sessao, $re, $nome, $estado, $email, $telefone, $cargo, $supervisor, $cn, $acesso, $ativo, $senha, $data, $hora);
} else
if ($acao === "transferenciaLista") {

    transferenciaLista($mysqli, $re_sessao);
} else 
if ($acao === "transfereColaborador") {

    $colaborador = $txtTitulo['colaborador'];
    $tipo = $txtTitulo['tipo'];

    transfereColaborador($mysqli, $tipo, $colaborador, $re_sessao, $data, $hora);
} else 
if ($acao === "USUARIOProcura") {

    $txt = $txtTitulo['txt'];
    $cn = $txtTitulo['cn'];

    if ($cn === "0" && $txt === "") {

        $erro = 1;
        $msg = "Informações incompletas!";

        $arr = array("erro" => $erro, "msg" => $msg);
        echo JsonEncodePAcentos::converter($arr);
    } else {

        usuarioProcura($mysqli, $re_sessao, $txt, $cn, $uf_sessao);
    }
}
function dados($mysqli, $re)
{
    $sql = "select usr.re as re, usr.nome as nome, usr.estado as estado, usr.supervisor as coordenador, usr.cargo as cargo, usr.email as email, usr.telefone as telefone, usr.cn as cn, usr.sistema as sistema, usr.ativo as ativo from usuario usr WHERE usr.re='{$re}'";

    $result = $mysqli->query($sql);
    $row = $result->fetch_array();

    $result->close();

    echo JsonEncodePAcentos::converter($row);

    $mysqli->close();
}
function dadosCartao($mysqli, $re, $re_resp)
{
    $sql = "select usr.re as re, usr.nome as nome, IF(char_length(usr.cartao) = 6 ,usr.cartao,'S/CARTÃO') as cartao from usuario usr WHERE usr.re='{$re}'";

    $result = $mysqli->query($sql);
    $cartao = $result->fetch_array();

    $result->close();

    //Permissão : Atribuir cartão
    $p1 = permissaoVerifica($mysqli, "8", $re_resp);

    //Permissão : Solicitar desbloqueio de cartão
    $p2 = permissaoVerifica($mysqli, "83", $re_resp);

    $arr = array("cartao" => $cartao, "permissao1" => $p1, "permissao2" => $p2);

    echo JsonEncodePAcentos::converter($arr);

    $mysqli->close();
}
function dadosFrota($mysqli, $re, $re_resp)
{
    $sql = "select usr.re as re, usr.nome as nome, IF(char_length(usr.frota) = 7 ,usr.frota,'S/FROTA') as placa, f.km as km from usuario usr left join frota f on f.placa=usr.frota WHERE usr.re='{$re}'";

    $result = $mysqli->query($sql);
    $frota = $result->fetch_array();

    $result->close();

    $p = permissaoVerifica($mysqli, "9", $re_resp);

    $arr = array("frota" => $frota, "permissao" => $p);

    echo JsonEncodePAcentos::converter($arr);

    $mysqli->close();
}

function sistemaLista()
{
    $arr = array(
        array("id" => "1", "nome" => "NÃO"),
        array("id" => "2", "nome" => "SIM")
    );

    echo JsonEncodePAcentos::converter($arr);
}
function ativoLista()
{
    $arr = array(
        array("id" => "1", "nome" => "NÃO"),
        array("id" => "2", "nome" => "SIM")
    );

    echo JsonEncodePAcentos::converter($arr);
}
function estadoLista($mysqli, $re_sessao)
{

    $p = permissaoVerifica($mysqli, "861", $re_sessao);

    $sqlV = "select cn.regiao as regiao from usuario u inner join cn on cn.id=u.cn where u.re='{$re_sessao}'";
    $result = $mysqli->query($sqlV)->fetch_array();
    $regiao = $result['regiao'];

    if ($p === 0) {
        $sql = "select id, sigla as nome from uf where regiao='{$regiao}' order by nome desc";
    } else {
        $sql = "select id, sigla as nome from uf order by nome desc";
    }    

    $myArray = array();
    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }
    $mysqli->close();
}
function cargoLista($mysqli)
{
    $sql = "select id, nome from cargo order by nome";

    $myArray = array();
    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }
    $mysqli->close();
}
function coordenadorLista($mysqli)
{
    $sql = "select concat(uf.sigla,' - ',u.nome) as nome, u.re as re from permissao p inner join usuario u on u.re=p.colaborador inner join uf uf on uf.id=u.estado WHERE p.funcao='66' order by u.estado, nome asc";

    $myArray = array();
    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }
    $mysqli->close();
}
function cartaoLista($mysqli)
{
    $sql = "select c.controle as cartao from cartao c where ((select count(cartao) from usuario WHERE cartao=c.controle)+(select count(cartao) from gmg WHERE cartao=c.controle))=0 order by c.controle asc";

    $myArray = array();
    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }
    $mysqli->close();
}
function veiculoLista($mysqli)
{
    $sql = "select v.vei_id as id, concat(v.vei_marca,'-',v.vei_modelo) as veiculo from veiculo v order by v.vei_marca";

    $myArray = array();
    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }
    $mysqli->close();
}
function frotaProcura($mysqli, $txt, $atual)
{
    $sql = "select f.placa as placa, u.re as re, ifnull(u.nome,'DISPONÍVEL') as nome, v.vei_marca as marca, v.vei_modelo as modelo from frota f inner join veiculo v on v.vei_id=f.veiculo left join usuario u on u.frota=f.placa where f.placa !='{$atual}' and (f.placa like '%" . $txt . "%' or v.vei_marca like '%" . $txt . "%' or v.vei_modelo like '%" . $txt . "%') order by f.placa asc";

    $myArray = array();
    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }
    $mysqli->close();
}
function frotaSeleciona($mysqli, $frota)
{
    $sql = "select f.placa as placa, u.re as re, ifnull(u.nome,'NÃO CADASTRADO') as nome, c.re as cooRe, ifnull(c.nome,'NÃO CADASTRADO') as coordenador, concat(v.vei_marca,' ',v.vei_modelo) as veiculo from frota f inner join veiculo v on v.vei_id=f.veiculo left join usuario u left join usuario c on c.re=u.supervisor on u.frota=f.placa where f.placa='{$frota}'";

    if ($result = $mysqli->query($sql)) {
        $dados = $result->fetch_array(MYSQLI_ASSOC);

        echo JsonEncodePAcentos::converter($dados);
    }
    $mysqli->close();
}
function cnLista($mysqli, $uf_sessao, $re_sessao)
{
    $p = permissaoVerifica($mysqli, "30", $re_sessao);

    $sql = "select cn.regiao as regiao from usuario u inner join cn on cn.id=u.cn where u.re='{$re_sessao}'";
    $result = $mysqli->query($sql)->fetch_array();
    $regiao = $result['regiao'];

    if ($p === 0) {
        $sql = "select id, nome from cn where regiao='{$regiao}'";
    } else {
        $sql = "select id, nome from cn";
    }
    $myArray = array();
    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }
}
function cadastroUSUARIO($mysqli, $re_sessao, $re, $nome, $estado, $email, $telefone, $cargo, $coordenador, $cn, $acesso, $combustivel, $cartao, $data, $hora)
{
    $p = permissaoVerifica($mysqli, "14", $re_sessao);
    $p2 = permissaoVerifica($mysqli, "26", $re_sessao);
    $usuario = $mysqli->query("select u.re as re, u.nome as nome, u.estado as estado, uf.sigla as uf, u.cn as cn, cn.nome as cn1 from usuario u inner join cn on cn.id=u.cn inner join uf on uf.id=u.estado where re='{$re_sessao}'")->fetch_array(MYSQLI_ASSOC);

    $erro = "1";
    $vCartao = cartaoVerifica($mysqli, $cartao);
    if ($combustivel === "1") {
        $cartao = "";
    }

    if ($p === 0) {

        $msg = "Você não tem permissão para cadastrar usuários.";
    } else 
    if ($estado === "0") {
        $msg = "Necessário selecionar o estado.";
    } else
    if ($cn === "0") {
        $msg = "Necessário selecionar o CN.";
    } else
    if ($p2 === 0 and $usuario['cn'] != $cn) {
        $msg = "você só pode selecionar o cn: " . $usuario['cn1'] . ".";
    } else 
    if (strlen($re) != 5) {
        $msg = "RE informado é inválido.";
    } else 
    if (argVerifica($mysqli, "re", $re)) {
        $msg = "O RE informado já está cadastrado.";
    } else
    if ($nome === "" || strlen($nome) < 5) {
        $msg = "O nome informado é inválido.";
    } else
    if ($combustivel === "0") {
        $msg = "Necessário selecionar se o colaborador utiliza cartão de combustível.";
    } else
    if ($combustivel === "2" and preg_match('/^([1-9][0-9][0-9][0-9][0-9][0-9])$/', $cartao) === 0) {
        $msg = "O cartão informado é inválido.";
    } else
    if ($combustivel === "2" and $vCartao != "0") {
        $tempo = 3000;
        $msg = $vCartao;
    } else
    if ($email === "") {
        $msg = "Necessário informar o endereço de E-mail do colaborador.";
    } else
    if (argVerifica($mysqli, "email", $email) > 0) {
        $msg = "O E-mail informado já está cadastrado.";
    } else
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $msg = "O E-mail informado é inválido.";
    } else
    if (strlen($telefone) != 11 || preg_match('/^([0-9][0-9][1-9][1-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9])$/', $telefone) === 0) {
        $msg = "O telefone informado é inválido.";
    } else
    if (argVerifica($mysqli, "telefone", $telefone) > 0) {
        $msg = "O Telefone informado já está cadastrado.";
    } else
    if ($cargo === "0") {
        $msg = "Necessário selecionar o cargo do colaborador.";
    } else
    if ($acesso === "0") {
        $msg = "Necessário selecionar o acesso do colaborador.";
    } else
    if ($p2 === 0 and $acesso === "2") {
        $msg = "O acesso ao sistema dever ser selecionado 'NÃO'.";
    } else
    if ($coordenador === "0") {
        $msg = "Necessário selecionar o coordenador do colaborador.";
    } else 
    if ($p2 === 0 and $usuario['re'] != $coordenador) {
        $msg = "você só pode selecionar o coordenador: " . $usuario['nome'] . ".";
    } else {

        $senha = substr(md5($data . $hora . $re), 0, 7);
        $senha_bd = md5($senha);

        $sql = "insert into usuario (re, email, telefone, cartao, senha, nome, cn, cargo, supervisor, sistema, estado, ativo, cadastro, data_cadastro, hora_cadastro) values ('{$re}', '{$email}', '{$telefone}','{$cartao}', '{$senha_bd}', '{$nome}', '{$cn}', '{$cargo}', '{$coordenador}', '{$acesso}','{$estado}', '2', '{$re_sessao}','{$data}','{$hora}')";
        if ($mysqli->query($sql)) {

            $sql_email = "insert into email (ema_re, ema_senha, ema_smtp) values ('{$re}', 'ND', 'smtp.office365.com')";
            $mysqli->query($sql_email);
            $colaborador = dados_novo_colaborador($mysqli, $re);

            if ($combustivel === "2") {
                $obs = "Cartão do colaborador " . $nome . "-" . $re;
                $sql_cartao = "insert into cartao (controle, obs, re_cadastro, status, data_cadastro, hora_cadastro, finalidade) values ('{$cartao}', '{$obs}', '{$re_sessao}','1','{$data}','{$hora}','1')";

                $mysqli->query($sql_cartao);
            }
            $body1 = bodyHtml_informe_cadastro($colaborador, $data, $hora);
            $body2 = bodyHtml_colaborador($colaborador, $data, $hora, $senha);

            enviar($colaborador, $body1);

            if ($acesso === "2") {
                enviar_senha($colaborador, $body2);

                permissaoPadraoLista($mysqli, $re, $re_sessao, "1", $data);
                permissaoPadraoLista($mysqli, $re, $re_sessao, "2", $data);
            }
            $erro = "0";
            $msg = "Usuário cadastrado com sucesso.";
        }
    }
    $mysqli->close();
    $arr = array("erro" => $erro, "msg" => $msg);
    echo JsonEncodePAcentos::converter($arr);
}
function removeAcentos($string)
{
    $comAcentos = array('à', 'á', 'â', 'ã', 'ä', 'å', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ù', 'ü', 'ú', 'ÿ', 'À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'O', 'Ù', 'Ü', 'Ú');
    $semAcentos = array('a', 'a', 'a', 'a', 'a', 'a', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'y', 'A', 'A', 'A', 'A', 'A', 'A', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'N', 'O', 'O', 'O', 'O', 'O', '0', 'U', 'U', 'U');

    return str_replace($comAcentos, $semAcentos, $string);
}
function permissaoPadraoLista($mysqli, $re, $re_sessao, $tipo, $data)
{
    if ($tipo === "1") {
        $sql = "SELECT id FROM pagina WHERE padrao like '%campo%'";
    } else {
        $sql = "SELECT id FROM funcao WHERE padrao like '%campo%'";
    }

    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            if ($tipo === "1") {

                $pagina = $row['id'];
                $funcao = "0";
            } else {
                $pagina = "0";
                $funcao = $row['id'];
            }
            $mysqli->query("insert into permissao (colaborador, tipo, pagina, funcao, re, data) values ('{$re}','{$tipo}','{$pagina}','{$funcao}','{$re_sessao}','{$data}')");
        }
    }
}
function dados_novo_colaborador($mysqli, $re)
{
    $sql = "select u.re as re, u.nome as nome, u.cartao as cartao, u.telefone as telefone, u.email as email, c.nome as coordenador_nome, c.email coordenador_email, resp.nome as resp_nome, resp.email as resp_email, ca.nome as cargo, cn.nome as cn, uf.sigla as uf from usuario u inner join usuario c on c.re=u.supervisor inner join usuario resp on resp.re=u.cadastro inner join cargo ca on ca.id=u.cargo inner join cn on cn.id=u.cn inner join uf on uf.id=cn.uf WHERE u.re='{$re}'";

    $result = $mysqli->query($sql);
    $arr = $result->fetch_array();

    $result->close();
    return $arr;
}
function enviar($dados, $body)
{
    $email_suporte = "ftsilva@icomontecnologia.com.br"; //"suporte@solicitacaooem.com.br"; //$dados['servidorSmtp']; // Specify main and backup SMTP servers
    $nome_suporte = "SUPORTE SOLICITAÇÕES O&M";
    $senha_suporte = "znfthdwhjwjqjfhv"; //&fKC0FcYp]]8"; //    $dados['servidorSenha'];              // SMTP password
    $smtp_suporte = "smtp.office365.com"; //mail.solicitacaooem.com.br"; //
    $porta_suporte = 587;

    $mail = new PHPMailer;

    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = $smtp_suporte;
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = $email_suporte;
    $mail->Password = $senha_suporte; //
    $mail->SMTPSecure = 'tls';                         // Enable TLS encryption, `ssl` also accepted
    $mail->CharSet = 'UTF-8';
    $mail->Encoding = 'base64';
    $mail->Port = $porta_suporte;

    $mail->setFrom($email_suporte, $nome_suporte); //E-mail origem

    $mail->addBCC('ftsilva@icomontecnologia.com.br', 'Felipe Teixeira');

    if ($dados['uf'] === "MG") {
        $mail->addAddress($dados['coordenador_email'], $dados['coordenador_nome']);
        $mail->addCC($dados['email'], $dados['nome']); //Cópia
        $mail->addCC($dados['resp_email'], $dados['resp_nome']); //Cópia
        $mail->addCC('brpaiva@icomontecnologia.com.br', "Bruno Rosse"); //Cópia

    } else if ($dados['uf'] === "BA") {

        $mail->addAddress($dados['coordenador_email'], $dados['coordenador_nome']);
        $mail->addCC($dados['resp_email'], $dados['resp_nome']); //Cópia
        $mail->addCC('apassos@icomontecnologia.com.br', "Antonio Passos"); //Cópia

    }

    $mail->isHTML(true);                                  // Set email format to HTML

    $mail->Subject = "Novo colaborador cadastrado";
    $mail->Body = $body;
    $mail->AltBody = $body;

    $mail->send();
}
function bodyHtml_informe_cadastro($dados, $data, $hora)
{

    $pagina = '<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
<style>*{
    font-family: Helvetica;
}
.palco{
    width: 90%;
    margin: 4px auto;
    color: #444;
    border: 2px solid #ccc;
    padding: 4px;
    line-height: 10px;
}
.ln_assinatura{color:#878a85;line-height: 10px";font-size: 10px;
}
h1{
    text-align: center;
    font-size: 18px;
}
h2{
    text-align: center;
    color: #0016b0;
    font-size: 16px;
}
p.sub-titulo{
    font-size: 18px;
}
.center{
    text-align: center;
}
table{
    width: 100%;
    font-size: 12px;
}
table th{
    font-size: 14px;
    font-weight: normal;
    background-color: midnightblue;
    color:e1f0f0;
    border: none}
table td{
    font-size: 12px;
    font-weight: normal;
    background-color: transparent;
    color: #333333;
    text-align: center;
    border:solid 1px aliceblue;
}
      </style>
    </head>
    <body>';

    $html = "
         <h1>Cadastro de colaborador</h1>
         <p class='center sub-titulo'><strong>Dados cadastrais do novo colaborador</strong>
         <br><a style='color: #006DD9; text-decoration:none' title='Acessar sistema' href='https://oem.solicitacaooem.com.br'>Acessar Sistema</a>
         <br>
         <table style='border:solid 1px steelblue'>
         <tr>
         <td style='text-align: right;'><strong>NOME:</strong></td>
         <td style='text-align: left;'>" . $dados['nome'] . "</td>
         </tr>
         <tr>
         <td style='text-align: right;'><strong>E-MAIL:</strong></td>
         <td style='text-align: left;'>" . $dados['email'] . "</td>
         </tr>
         <tr>
         <td style='text-align: right;'><strong>TELEFONE:</strong></td>
         <td style='text-align: left;'>" . $dados['telefone'] . "</td>
         </tr>
         <tr>
         <td style='text-align: right;'><strong>CARTÃO:</strong></td>
         <td style='text-align: left; color:red'>" . $dados['cartao'] . "<strong> (SOLICITAR DESBLOQUEIO)</strong></td>
         </tr>
         <tr>
         <td style='text-align: right;'><strong>CARGO:</strong></td>
         <td style='text-align: left;'>" . $dados['cargo'] . "</td>
         </tr>
         <tr>
         <td style='text-align: right;'><strong>COORDENADOR:</strong></td>
         <td style='text-align: left;'>" . $dados['coordenador_nome'] . "</td>
         </tr>
         <tr>
         <td style='text-align: right;'><strong>CN:</strong></td>
         <td style='text-align: left;'>" . $dados['cn'] . "-" . $dados['uf'] . "</td>
         </tr>
         </table>
        <p class='direita'> Data/Hora do cadastro: " . $data . " " . $hora . "</p>
	 	<p class='ln_assinatura'>Responsável: <strong>" . $dados['resp_nome'] . "</strong>
<br>E-mail:" . $dados['resp_email'] . "</p>
</body></html>";

    $body = $pagina . $html;

    return $body;
}
function enviar_senha($dados, $body)
{
    $email_suporte = "ftsilva@icomontecnologia.com.br"; //"suporte@solicitacaooem.com.br"; //$dados['servidorSmtp']; // Specify main and backup SMTP servers
    $nome_suporte = "SUPORTE SOLICITAÇÕES O&M";
    $senha_suporte = "znfthdwhjwjqjfhv"; //&fKC0FcYp]]8"; //    $dados['servidorSenha'];              // SMTP password
    $smtp_suporte = "smtp.office365.com"; //mail.solicitacaooem.com.br"; //
    $porta_suporte = 587;

    $mail = new PHPMailer;

    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = $smtp_suporte;
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = $email_suporte;
    $mail->Password = $senha_suporte; //
    $mail->SMTPSecure = 'tls';                         // Enable TLS encryption, `ssl` also accepted
    $mail->CharSet = 'UTF-8';
    $mail->Encoding = 'base64';
    $mail->Port = $porta_suporte;

    $mail->setFrom($email_suporte, $nome_suporte); //E-mail origem

    $mail->addAddress($dados['email'], $dados['nome']);
    $mail->addBCC('ftsilva@icomontecnologia.com.br', 'Felipe Teixeira');

    $mail->isHTML(true);                                  // Set email format to HTML

    $mail->Subject = "Senha de acesso ao sistema";
    $mail->Body = $body;
    $mail->AltBody = $body;

    $mail->send();
}
function bodyHtml_colaborador($dados, $data, $hora, $senha)
{
    $pagina = '<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
<style>*{
    font-family: Helvetica;
}
.palco{
    width: 90%;
    margin: 4px auto;
    color: #444;
    border: 2px solid #ccc;
    padding: 4px;
    line-height: 10px;
}
.ln_assinatura{color:#878a85;line-height: 10px";font-size: 10px;
}
h1{
    text-align: center;
    font-size: 18px;
}
h2{
    text-align: center;
    color: #0016b0;
    font-size: 16px;
}
p.sub-titulo{
    font-size: 18px;
}
.direita{
    text-align: right;
    color:#878a85;
}
.senha {font-weight: bold; color: red}

.center{
    text-align: center;
}
table{
    width: 100%;
    font-size: 12px;
    border-collapse: collapse;
    margin-top: 2px;
}
table th{
    font-size: 14px;
    font-weight: normal;
    background-color: midnightblue;
    color:e1f0f0;
    border: none}
table td{
    font-size: 12px;
    font-weight: normal;
    background-color: transparent;
    color: #333333;
    text-align: center;
}
table tr:nth-child(even) {
    background: aliceblue;
}
table tfoot tr td{
    font-size: 14px;
    font-weight: normal;
    background-color: #444;
    color: #ccc;
    border: none
}
      </style>
    </head>
    <body>';

    $html = "
	 <div class='palco'>
         <h1>Senha de acesso ao sistema</h1>
         <h2>Solicitante: " . $dados['nome'] . "</h2>
         <p class='center sub-titulo'>Solicitação: <strong>Favor alterar a senha no primeiro acesso!</strong>
         <br><a style='color: #006DD9; text-decoration:none' title='Acessar sistema' href='https://oem.solicitacaooem.com.br'>Acessar Sistema</a>
	 	<p class='senha'>SENHA: " . $senha . "</p></div>" .
        "<p class='direita'> Data/Hora da solicitação: " . $data . " " . $hora . "</p>
	 	<p class='ln_assinatura'>Responsável pelo suporte: <strong>" . $dados['resp_nome'] . "</strong>
<br>E-mail:" . $dados['resp_email'] . "</p>
</body></html>";

    $body = $pagina . $html;

    return $body;
}
function editaUSUARIO($mysqli, $re_sessao, $re, $nome, $estado, $email, $telefone, $cargo, $supervisor, $cn, $acesso, $ativo, $senha, $data, $hora)
{
    $erro = "1";
    $cpCoordenador = "";
    $p = permissaoVerifica($mysqli, "15", $re_sessao);
    $p2 = permissaoVerifica($mysqli, "27", $re_sessao);
    $usuario = $mysqli->query("select u.re as re, u.nome as nome, u.estado as estado, uf.sigla as uf, u.cn as cn, cn.nome as cn1 from usuario u inner join cn on cn.id=u.cn inner join uf on uf.id=u.estado where re='{$re_sessao}'")->fetch_array(MYSQLI_ASSOC);

    if ($p === 0) {
        $msg = "<i class='icon-attention'></i> Você não tem permissão para editar usuários.";
    } else
    if ($acesso === "0") {
        $msg = "<i class='icon-attention'></i> Necessário selecionar o acesso do colaborador ao sistema.";
    } else
    if ($ativo === "0") {
        $msg = "<i class='icon-attention'></i> Necessário selecionar se o colaborador está ativo.";
    } else
    if ($estado === "0") {
        $msg = "<i class='icon-attention'></i> Necessário selecionar o estado(UF).";
    } else
     if ($cn === "0") {
        $msg = "<i class='icon-attention'></i> Necessário selecionar o CN.";
    } else
        //    if ($p2 === 0 and $usuario['cn'] != $cn) {
        //        $msg = "você só pode selecionar o cn: " . $usuario['cn1'] . ".";
        //    } else 
        if (!$re || $re === "") {
            $msg = "<i class='icon-attention'></i> Um campo obrigatório não foi preenchido!!";
        } else if (!$nome || $nome === "") {
            $msg = "<i class='icon-attention'></i> Necessário informar o nome do colaborador.";
        } else 
    if ($ativo != "2" and (!$telefone || $telefone === "")) {
            $msg = "<i class='icon-attention'></i> Necessário informar o telefone do colaborador.";
        } else 
    if ($acesso === "2" and !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $msg = "<i class='icon-attention'></i> O e-mail informado é inválido.";
        } else
    if ($ativo != "2" and (!preg_match('/[1-9]{2}[0-9]{9}$/', $telefone))) {
            $msg = "<i class='icon-attention'></i> O telefone informado é inválido.";
        } else
    if ($acesso === "2" and (argVerifica($mysqli, $re,  "email", $email) > 0)) {
            $msg = "<i class='icon-attention'></i> E-mail já cadastrado para outro colaborador.";
        } else 
    if ($ativo != "2" and (argVerifica($mysqli, $re,  "telefone", $telefone) > 0)) {
            $msg = "<i class='icon-attention'></i> Telefone já cadastrado para outro colaborador.";
        } else
    if ($senha <> "" and !preg_match("/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{7,}$/", $senha)) {
            $msg = "<i class='icon-attention'></i> A nova senha deve conter 4 caracteres ou mais, números e letras.";
        } else {

            if ($ativo === "1") {
                $telefone = "";
                $acesso = "1";
            }

            if ($usuario['re'] === $supervisor) {
                $cpCoordenador = "supervisor='{$supervisor}'";
            } else
        if ($p2 > 0 and $usuario['re'] != $supervisor) {
                $cpCoordenador = "supervisor='{$supervisor}'";
            } else
        if ($p2 === 0 and $usuario['re'] != $supervisor) {
                $cpCoordenador = "transferencia='{$supervisor}'";
            }

            if ($senha <> "") {
                $senha = md5($senha);
                $sql = "update usuario set email='{$email}', telefone='{$telefone}', senha='{$senha}', nome='{$nome}', estado='{$estado}', cn='{$cn}', cargo='{$cargo}', " . $cpCoordenador . ", sistema='{$acesso}', ativo='{$ativo}' where re='{$re}'";
            } else {
                $sql = "update usuario set email='{$email}', telefone='{$telefone}', nome='{$nome}', estado='{$estado}', cn='{$cn}', cargo='{$cargo}', " . $cpCoordenador . ", sistema='{$acesso}', ativo='{$ativo}' where re='{$re}'";
            }

            //Armazena dados antes da mudança
            $d = $mysqli->query("select usr.re as re, usr.nome as nome, usr.estado as ufId,uf.sigla as uf, usr.supervisor as coordenadorRe, co.nome as coordenador, usr.cargo as cargoId, c.nome as cargo, usr.email as email, usr.telefone as telefone, usr.cn as cnId, cn.nome as cn, usr.sistema as acessoId, if(usr.sistema=2,'SIM','NAO') as acesso, usr.ativo as statusId, if(usr.ativo=2,'ATIVO','DESATIVADO') as status from usuario usr left join uf on uf.id=usr.estado left join cn on cn.id=usr.cn left join cargo c on c.id=usr.cargo left join usuario co on usr.supervisor=co.re WHERE usr.re='{$re}'")->fetch_array(MYSQLI_ASSOC);
            if ($mysqli->query($sql)) {

                $d2 = $mysqli->query("select usr.re as re, usr.nome as nome, usr.estado as ufId,uf.sigla as uf, usr.supervisor as coordenadorRe, co.nome as coordenador, usr.cargo as cargoId, c.nome as cargo, usr.email as email, usr.telefone as telefone, usr.cn as cnId, cn.nome as cn, usr.sistema as acessoId, if(usr.sistema=2,'SIM','NAO') as acesso, usr.ativo as statusId, if(usr.ativo=2,'ATIVO','DESATIVADO') as status from usuario usr left join uf on uf.id=usr.estado left join cn on cn.id=usr.cn left join cargo c on c.id=usr.cargo left join usuario co on usr.supervisor=co.re WHERE usr.re='{$re}'")->fetch_array(MYSQLI_ASSOC);

                if ($d['uf'] != $d2['uf']) {

                    historico($mysqli, $re_sessao, $re, "4", $d['uf'], $d2['uf'], $data, $hora);
                }
                if ($d['cn'] != $d2['cn']) {

                    historico($mysqli, $re_sessao, $re, "5", $d['cn'], $d2['cn'], $data, $hora);
                }
                if ($d['nome'] != $d2['nome']) {
                    historico($mysqli, $re_sessao, $re, "6", $d['nome'], $d2['nome'], $data, $hora);
                }
                if ($d['email'] != $d2['email']) {
                    historico($mysqli, $re_sessao, $re, "7", $d['email'], $d2['email'], $data, $hora);
                }
                if ($d['telefone'] != $d2['telefone']) {
                    historico($mysqli, $re_sessao, $re, "8", $d['telefone'], $d2['telefone'], $data, $hora);
                }
                if ($d['cargoId'] != $d2['cargoId']) {
                    historico($mysqli, $re_sessao, $re, "9", $d['cargo'], $d2['cargo'], $data, $hora);
                }
                if ($d['acessoId'] != $d2['acessoId']) {
                    historico($mysqli, $re_sessao, $re, "11", $d['acesso'], $d2['acesso'], $data, $hora);
                }
                if ($d['statusId'] != $d2['statusId']) {
                    historico($mysqli, $re_sessao, $re, "12", $d['status'], $d2['status'], $data, $hora);
                }

                $erro = "0";
                $msg = "<i class='icon-ok-circle-1'></i> Colaborador editado com sucesso.";
            }
        }

    $mysqli->close();
    $arr = array("erro" => $erro, "msg" => $msg);
    echo JsonEncodePAcentos::converter($arr);
}
function transferenciaLista($mysqli, $re)
{
    $sql = "select u.re as re, u.nome as nome, c.nome as coordenador, cn.nome as cn from usuario u inner join usuario c on c.re=u.supervisor inner join cn cn on cn.id=u.cn where u.transferencia='{$re}'";

    $myArray = array();
    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }
    $mysqli->close();
}
function transfereColaborador($mysqli, $tipo, $colaborador, $novoCoordenador, $data, $hora)
{
    $erro = "1";
    $msg = "Erro";

    if ($tipo === "aceita") {
        $sql = "update usuario set supervisor='{$novoCoordenador}', transferencia='0' where re='{$colaborador}'";

        $atual = $mysqli->query("select c.re as re, c.nome as nome from usuario u inner join usuario c on c.re=u.supervisor where u.re='{$colaborador}'")->fetch_array(MYSQLI_ASSOC);
        $novo = $mysqli->query("select u.re as re, u.nome as nome from usuario u where u.re='{$novoCoordenador}'")->fetch_array(MYSQLI_ASSOC);

        $atual_coordenador = $atual['nome'] . " [" . $atual['re'] . "]";
        $novo_coordenador = $novo['nome'] . " [" . $novo['re'] . "]";

        if ($mysqli->query($sql)) {

            historico($mysqli, $atual['re'], $colaborador, "10", $atual_coordenador, $novo_coordenador, $data, $hora);

            $erro = "0";
            $msg = "Colaborador aceito com sucesso.";
        } else {
            $msg = "Erro ao aceitar colaborador.";
        }
    } else {
        $sql = "update usuario set transferencia='0' where re='{$colaborador}'";

        if ($mysqli->query($sql)) {
            $erro = "0";
            $msg = "Colaborador recusado com sucesso.";
        } else {
            $msg = "Erro ao recusar colaborador.";
        }
    }
    $mysqli->close();
    $arr = array("erro" => $erro, "msg" => $msg);
    echo JsonEncodePAcentos::converter($arr);
}
function cadastroFrota($mysqli, $re_sessao, $re, $frotaAtual, $frotaNova, $veiculo, $km, $data, $hora)
{
    $p = permissaoVerifica($mysqli, "9", $re_sessao);
    $vPlaca = preg_match('/^([A-Z][A-Z][A-Z])([0-9][A-Za-z0-9][0-9][0-9])$/', $frotaNova);

    $erro = "1";
    if ($p === 0) {
        $msg = "<i class='icon-attention'></i> Você não tem permissão para cadastrar veículos.";
    } else
    if ($vPlaca === 0) {
        $msg = "<i class='icon-attention'></i> A placa digitada é inválida.";
    } else
    if ($veiculo === 0) {
        $msg = "<i class='icon-attention'></i> Necessário selecionar o veículo.";
    } else
    if ($km === "") {
        $msg = "<i class='icon-attention'></i> Necessário informar o KM do veículo.";
    } else {

        $sql = "insert into frota (placa, veiculo, km, re_cadastro, ultimo_colaborador) values ('{$frotaNova}', '{$veiculo}', '{$km}','{$re_sessao}', '{$re}')";

        if ($mysqli->query($sql)) {

            $sql = "update usuario set frota='{$frotaNova}' where re='{$re}'";
            if ($mysqli->query($sql)) {
                $erro = "0";
                $msg = "<i class='icon-ok-circle-1'></i> Veículo cadastrado com sucesso!";

                historico($mysqli, $re_sessao, $re, "2", $frotaAtual, $frotaNova, $data, $hora);
            } else {
                $msg = "<i class='icon-attention'></i> Erro ao cadastrar novo veículo.";
            }
        } else {
            $msg = "<i class='icon-attention'></i> Erro ao cadastrar novo veículo.";
        }
    }
    $mysqli->close();
    $arr = array("erro" => $erro, "msg" => $msg);
    echo JsonEncodePAcentos::converter($arr);
}
function cartaoAltera($mysqli, $re_sessao, $re, $cartaoAtual, $cartaoNovo, $cartaoMotivo, $data, $hora)
{
    $erro = "1";
    $p = permissaoVerifica($mysqli, "7", $re_sessao);
    $verificaCartao = $mysqli->query("select controle from cartao where controle='{$cartaoNovo}'")->num_rows;

    if ($p === 0) {
        $msg = "<i class='icon-attention'></i> Você não tem permissão.";
    } else
    if (strlen($cartaoNovo) != 6) {
        $msg = "<i class='icon-attention'></i> O novo cartão informado é inválido.";
    } else
   if ($verificaCartao > 0) {
        $msg = "<i class='icon-attention'></i> O novo cartão informado já está cadastrado no sistema.";
    } else
    if ($cartaoNovo == $cartaoAtual) {
        $msg = "<i class='icon-attention'></i> O novo cartão atual e novo são iguais.";
    } else
    if (strlen($cartaoMotivo) < 10) {
        $msg = "<i class='icon-attention'></i> O motivo da troca é insuficiente.";
    } else {

        $sql = "insert into cartao (controle, obs, anterior, re_cadastro, status, data_cadastro, hora_cadastro, finalidade) values ('{$cartaoNovo}', '{$cartaoMotivo}','{$cartaoAtual}', '{$re_sessao}','1','{$data}','{$hora}','1')";
        if ($mysqli->query($sql)) {

            if ($mysqli->query("update usuario set cartao='{$cartaoNovo}' where re='{$re}'")) {

                historico($mysqli, $re_sessao, $re, "1", $cartaoAtual, $cartaoNovo, $data, $hora);

                $erro = "0";
                $msg = "<i class='icon-ok-circle-1'></i> Alteração realizada com sucesso.";
            } else {
                $msg = "<i class='icon-attention'></i> Erro ao efetuar alteração.";
            }
        } else {
            $msg = "<i class='icon-attention'></i> Erro ao efetuar alteração.";
        }
    }

    $mysqli->close();
    $arr = array("erro" => $erro, "msg" => $msg);
    echo JsonEncodePAcentos::converter($arr);
}
function cartaoAtribui($mysqli, $re_sessao, $re, $cartaoAtual, $cartaoNovo, $data, $hora)
{
    $erro = "1";
    $p = permissaoVerifica($mysqli, "8", $re_sessao);
    $verificaCartao = $mysqli->query("select cartao from usuario where cartao='{$cartaoNovo}'")->num_rows;

    if ($p === 0) {
        $msg = "<i class='icon-attention'></i> Você não tem permissão.";
    } else
    if (strlen($cartaoNovo) < 6) {
        $msg = "<i class='icon-attention'></i> O novo cartão informado é inválido.";
    } else
   if ($verificaCartao > 0) {
        $msg = "<i class='icon-attention'></i> O novo cartão informado já está atribuído a outro colaborador.";
    } else
    if ($cartaoNovo == $cartaoAtual) {
        $msg = "<i class='icon-attention'></i> O novo cartão atual e novo são iguais.";
    } else {

        if ($mysqli->query("update usuario set cartao='{$cartaoNovo}' where re='{$re}'")) {

            historico($mysqli, $re_sessao, $re, "2", $cartaoAtual, $cartaoNovo, $data, $hora);

            $erro = "0";
            $msg = "<i class='icon-ok-circle-1'></i> Alteração realizada com sucesso.";
        } else {
            $msg = "<i class='icon-attention'></i> Erro ao efetuar alteração.";
        }
    }

    $mysqli->close();
    $arr = array("erro" => $erro, "msg" => $msg);
    echo JsonEncodePAcentos::converter($arr);
}
function cartaoDesbloqueio($mysqli, $re_sessao, $re, $cartaoAtual, $data, $hora)
{
    $erro = "1";
    $p = permissaoVerifica($mysqli, "83", $re_sessao);

    $verificaSolicitacao = $mysqli->query("select id from cartao where controle='{$cartaoAtual}' and status='1'")->num_rows;

    if ($p === 0) {
        $msg = "<i class='icon-attention'></i> Você não tem permissão.";
    } else
    if ($verificaSolicitacao > 0) {
        $msg = "<i class='icon-attention'></i> Solicitação já realizada, aguarde.";
    } else {

        if ($mysqli->query("update cartao set status='1', finalidade='1' where controle='{$cartaoAtual}'")) {

            historico($mysqli, $re_sessao, $re, "15", $cartaoAtual, "", $data, $hora);

            $erro = "0";
            $msg = "<i class='icon-ok-circle-1'></i> Desbloqueio solicitado com sucesso.";
        } else {
            $msg = "<i class='icon-attention'></i> Erro ao efetuar solicitação.";
        }
    }

    $mysqli->close();
    $arr = array("erro" => $erro, "msg" => $msg);
    echo JsonEncodePAcentos::converter($arr);
}
function cartaoRemove($mysqli, $re_sessao, $re, $cartaoAtual, $data, $hora)
{
    $erro = "1";
    $p = permissaoVerifica($mysqli, "19", $re_sessao);

    if ($p === 0) {
        $msg = "<i class='icon-attention'></i> Você não tem permissão.";
    } else {

        if ($mysqli->query("update usuario set cartao='' where cartao='{$cartaoAtual}' and re='{$re}'")) {

            historico($mysqli, $re_sessao, $re, "2", $cartaoAtual, "", $data, $hora);

            $erro = "0";
            $msg = "<i class='icon-ok-circle-1'></i> Cartão removido com sucesso.";
        } else {
            $msg = "<i class='icon-attention'></i> Erro ao efetuar alteração.";
        }
    }

    $mysqli->close();
    $arr = array("erro" => $erro, "msg" => $msg);
    echo JsonEncodePAcentos::converter($arr);
}
function frotaAlteraKm($mysqli, $re_sessao, $re, $frota, $kmAtual, $kmNovo, $data, $hora)
{
    $erro = "1";
    $p = permissaoVerifica($mysqli, "17", $re_sessao);

    if ($p === 0) {
        $msg = "<i class='icon-attention'></i> Você não tem permissão.";
    } else {

        if ($mysqli->query("update frota set km='{$kmNovo}' where placa='{$frota}'")) {

            historico($mysqli, $re_sessao, $re, "1", $frota . "->" . $kmAtual, $frota . "->" . $kmNovo, $data, $hora);

            $erro = "0";
            $msg = "<i class='icon-ok-circle-1'></i> Km alterado com sucesso.";
        } else {
            $msg = "<i class='icon-attention'></i> Erro ao efetuar alteração." . "update frota set km='{$kmNovo}' where placa='{$frota}' and re='{$re}'";
        }
    }

    $mysqli->close();
    $arr = array("erro" => $erro, "msg" => $msg);
    echo JsonEncodePAcentos::converter($arr);
}
function frotaAtribui($mysqli, $re_sessao, $re, $atual, $novo, $data, $hora)
{
    $erro = "1";
    $tempo = "";
    $p = permissaoVerifica($mysqli, "9", $re_sessao);

    $verificaFrota = $mysqli->query("select frota from usuario where frota='{$novo}'")->num_rows;

    if ($p === 0) {
        $msg = "<i class='icon-attention'></i> Você não tem permissão.";
    } else
   if ($verificaFrota > 0) {

        $dadosAtual = $mysqli->query("select nome from usuario where re='{$re}'")->fetch_assoc();
        $dadosNovo = $mysqli->query("select nome from usuario where frota='{$novo}'")->fetch_assoc();
        $tempo = 6000;
        $msg = "<i class='icon-attention'></i> Esse veículo já está atribuído ao <span class='font-weight-bold'>" . $dadosNovo['nome'] . "</span>, remova antes de atribuir ao <span class='font-weight-bold'>" . $dadosAtual['nome'] . ".";
    } else {

        if ($mysqli->query("update usuario set frota='{$novo}' where re='{$re}'")) {

            $sql_ult = "update frota set ultimo_colaborador='{$re}' where placa='{$novo}'";
            $mysqli->query($sql_ult);

            historico($mysqli, $re_sessao, $re, "3", $atual, $novo, $data, $hora);

            $erro = "0";
            $msg = "<i class='icon-ok-circle-1'></i> Alteração realizada com sucesso.";
        } else {
            $msg = "<i class='icon-attention'></i> Erro ao efetuar alteração.";
        }
    }

    $mysqli->close();
    $arr = array("erro" => $erro, "msg" => $msg, "tempo" => $tempo);
    echo JsonEncodePAcentos::converter($arr);
}
function frotaRemove($mysqli, $re_sessao, $re, $frotaAtual, $data, $hora)
{
    $erro = "1";
    $p = permissaoVerifica($mysqli, "20", $re_sessao);

    if ($p === 0) {
        $msg = "<i class='icon-attention'></i> Você não tem permissão.";
    } else {

        if ($mysqli->query("update usuario set frota='' where frota='{$frotaAtual}' and re='{$re}'")) {

            historico($mysqli, $re_sessao, $re, "3", $frotaAtual, "REMOV", $data, $hora);

            $erro = "0";
            $msg = "<i class='icon-ok-circle-1'></i> Veículo removido com sucesso.";
        } else {
            $msg = "<i class='icon-attention'></i> Erro ao efetuar alteração.";
        }
    }

    $mysqli->close();
    $arr = array("erro" => $erro, "msg" => $msg);
    echo JsonEncodePAcentos::converter($arr);
}
function argVerifica($mysqli, $tipo, $conteudo)
{

    $sql = "select " . $tipo . " from usuario where " . $tipo . "='{$conteudo}'";

    $num = $mysqli->query($sql)->num_rows;
    return $num;
}
function cartaoVerifica($mysqli, $cartao)
{
    $G = $mysqli->query("select id from gmg where cartao='{$cartao}'")->num_rows;
    $U = $mysqli->query("select id from usuario where cartao='{$cartao}'")->num_rows;
    $C = $mysqli->query("select id from cartao where controle='{$cartao}'")->num_rows;

    $retorno = "0";

    if ($U > 0) {
        $dados = $mysqli->query("select u.re as re, u.nome as nome, u.estado as estado, uf.sigla as uf, u.cn as cn, cn.nome as cn1 from usuario u inner join cn on cn.id=u.cn inner join uf on uf.id=u.estado where u.cartao='{$cartao}'")->fetch_array(MYSQLI_ASSOC);

        $retorno = "O cartão informado já está cadastrado e atribuído ao colaborador " . $dados['nome'] . ", do CN " . $dados['cn1'];
    } else 
    if ($G > 0) {
        $dados = $mysqli->query("select g.identificacao, gt.nome as tipo, uf.sigla as uf, cn.nome as cn from gmg g inner join gmg_tipo gt on gt.id=g.tipo left join cn on cn.id=g.cn left join uf on uf.id=g.estado where g.cartao='{$cartao}'")->fetch_array(MYSQLI_ASSOC);

        $retorno = "O cartão informado já está cadastrado e atribuído ao " . $dados['tipo'] . "_" . $dados['identificacao'] . ", do CN " . $dados['cn'];
    } else 
    if ($C > 0) {
        $retorno = "O cartão informado já está cadastrado e desativado no sistema, verifique o número de controle e tente novamente.";
    }

    return $retorno;
}
function usuarioProcura($mysqli, $re_sessao, $txt, $cn, $uf)
{
    $txt = strtoupper($txt);
    $p = permissaoVerifica($mysqli, "21", $re_sessao);
    $p2 = permissaoVerifica($mysqli, "64", $re_sessao);


    $sql = "select cn.regiao as regiao from usuario u inner join cn on cn.id=u.cn where u.re='{$re_sessao}'";
    $result = $mysqli->query($sql)->fetch_array();
    $regiao = $result['regiao'];

    $where = "";

    if ($cn != "0") {
        $where .= " usr.cn='{$cn}'";
    }

    if ($where != "" and substr($where, -3) != "and") {
        $where .= " and";
    }

    if ($txt != "") {
        $where .= " (usr.re like '%" . $txt . "%' or usr.telefone like '%" . $txt . "%' or usr.nome like '%" . $txt . "%' or cor.nome like '%" . $txt . "%' or usr.cartao like '%" . $txt . "%')";
    }

    if ($where != "" and substr($where, -3) != "and") {
        $where .= " and";
    }

    if (substr($where, -3) === "and") {
        $where =  substr($where, 0, (strlen($where) - 3));
    }

    if ($p > 0) {

        $sql = "select usr.id as id, usr.re as re, usr.nome as nome, IF(char_length(usr.frota) = 7 ,usr.frota,'S/VEÍCULO') as veiculo, IF(char_length(usr.cartao) = 6 ,usr.cartao,'S/CARTÃO') as cartao, cor.nome as coordenador, cn.nome as cn, if(usr.ativo=2,'SIM','NÃO') as ativo from usuario usr inner join cn cn on cn.id=usr.cn inner join usuario cor on cor.re=usr.supervisor WHERE" . $where . " order by cor.nome, usr.nome asc";
    } else
    if ($p2 > 0) {

        $sql = "select usr.id as id, usr.re as re, usr.nome as nome, IF(char_length(usr.frota) = 7 ,usr.frota,'S/VEÍCULO') as veiculo, IF(char_length(usr.cartao) = 6 ,usr.cartao,'S/CARTÃO') as cartao, cor.nome as coordenador, cn.nome as cn, if(usr.ativo=2,'SIM','NÃO') as ativo from usuario usr inner join cn cn on cn.id=usr.cn inner join usuario cor on cor.re=usr.supervisor WHERE cn.regiao='{$regiao}' and " . $where . " order by cor.nome, usr.nome asc";
    } else
    if ($p === 0) {

        $sql = "select usr.id as id, usr.re as re, usr.nome as nome, IF(char_length(usr.frota) = 7 ,usr.frota,'S/VEÍCULO') as veiculo, IF(char_length(usr.cartao) = 6 ,usr.cartao,'S/CARTÃO') as cartao, cor.nome as coordenador, cn.nome as cn, if(usr.ativo=2,'SIM','NÃO') as ativo from usuario usr inner join cn cn on cn.id=usr.cn inner join usuario cor on cor.re=usr.supervisor WHERE usr.supervisor='{$re_sessao}' and " . $where . " order by cor.nome, usr.nome asc";
    }

    $myArray = array();
    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }
    $mysqli->close();
}
function permissaoVerifica($mysqli, $funcao, $re)
{
    $num = $mysqli->query("select id from permissao where funcao='{$funcao}' and colaborador='{$re}'")->num_rows;
    return $num;
}
function historico($mysqli, $re, $re_alterado, $tipo, $valorAtual, $valorNovo, $data, $hora)
{

    $sql = "insert into alteracao (re, re_alterado, tipo, valor_anterior, valor_novo, data, hora) values ('{$re}', '{$re_alterado}', '{$tipo}', '{$valorAtual}', '{$valorNovo}', '{$data}', '{$hora}')";

    $mysqli->query($sql);
}
