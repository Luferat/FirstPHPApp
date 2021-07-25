<?php

/**
 * config.php
 * Arquivo de configuração global das páginas do aplicativo.
 */

// PHP com UTF-8
header('Content-Type: text/html; charset=utf-8');

// Define variáveis do tema
$article = $aside = '';

// Declara variáveis das páginas
$title = $css = $js = $meta_tags = $page_css = $page_js = '';

// Faz conexão com MySQL/MariaDB
// Os dados da conexão estão em "theme/config.ini"
$i = parse_ini_file($_SERVER['DOCUMENT_ROOT'].'/config/config.ini', true);
foreach ($i as $k => $v) :
    if ($_SERVER['SERVER_NAME'] == $k):

        // Conexão com MySQL/MariaDB usando "mysqli" (orientada a objetos)
        @$conn = new mysqli($v['server'], $v['user'], $v['password'], $v['database']);

        // Trata possíveis exceções
        if ($conn->connect_error) die("Falha de conexão com o banco e dados: " . $conn->connect_error);
    endif;
endforeach;

// Seta transações com MySQL/MariaDB para UTF-8
$conn->query("SET NAMES 'utf8'");
$conn->query('SET character_set_connection=utf8');
$conn->query('SET character_set_client=utf8');
$conn->query('SET character_set_results=utf8');

// Seta dias da semana e meses do MySQL/MariaDB para "português do Brasil"
$conn->query('SET GLOBAL lc_time_names = pt_BR');
$conn->query('SET lc_time_names = pt_BR');

// Array com as configurações do tema
$C = array(
    'favicon' => '/img/logo01.png', // Ícone dos favoritos
    'appLogo' => '/img/logo02.png', // Logotipo
    'appTitle' => 'Meu Primeiro App', // Título do aplicativo
    'appSlogan' => 'Primeiros passos no PHP', // Slogan do aplicativo
    'appYear' => '2021', // Ano inicial do copyright
    'appOwner' => 'Seu Nome', // Proprietário do copyright
    'backgroundColor' => 'rgb(113, 34, 43)', // Cor de fundo do aplicativo
    'backgroundImage' => '/img/background02.jpg', // Imagem de fundo do aplicativo
    'meta' => array( // Meta tags do aplicativo
        'author' => 'Seu nome',
        'copyright' => 'Seu nome',
        'description' => 'Aplicativo modelo para desenvolvimento de aplicativos Web full stack com PHP.',
        'keywords' => 'HTML, CSS, JavaScript, PHP, MySQL, aplicativo, Web, fullstack, back-end, front-end, dinâmico, flexbox',
    ) 
);

// Lê configurações do tema da tabela "config" do banco de dados
$res = $conn->query("SELECT * FROM config");

// Gera array de configuração do tema ($C)
while ($data = $res->fetch_assoc()) :

    if (substr($data['var'], 0, 5) == 'meta_') :

        // Obtém lista de meta tags (Atributo "name")
        $var = str_ireplace('meta_', '', $data['var']);
        $C['meta'][$var] = $data['val'];
    else :

        // Todas as outras variáveis
        $C[$data['var']] = $data['val'];
    endif;

endwhile;

// print_r($C); exit;