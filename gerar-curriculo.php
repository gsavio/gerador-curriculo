<?php
use Mpdf\Mpdf;

require_once __DIR__ . '/vendor/autoload.php';

if(!is_dir('tmp/')) {
    mkdir(__DIR__ . '/tmp');
}

$defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
$fontDirs = $defaultConfig['fontDir'];

$defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
$fontData = $defaultFontConfig['fontdata'];

$config = [
    'tempDir' => __DIR__ . '/tmp',
    'fontDir' => array_merge($fontDirs, [__DIR__ . '/fonts']),
    'fontdata' => $fontData + [ 
        'opensans' => [
            'R' => 'OpenSans-Regular.ttf',
            'I' => 'OpenSans-Italic.ttf',
            'B' => 'OpenSans-Bold.ttf',
        ],
    ],
    'default_font' => 'opensans'
];

$mpdf = new Mpdf($config);

$nome = trim($_POST['nome']);

ob_start();

?>
<head>
<link rel="stylesheet" href="css/curriculo.css">
</head>
<div id="curriculoGerado">
    
    <h1><?php echo trim($nome); ?></h1>
    <div class="primeira-linha">
        <div><?php echo trim($_POST['principal_funcao']); ?></div>
        <div class="endereco"><?php echo trim($_POST['endereco']); ?></div>
    </div>
    <div class="segunda-linha">
        <div><?php echo trim($_POST['github']); ?></div>
        <div class="email"><?php echo trim($_POST['email']); ?></div>
        <div class="telefone"><?php echo trim($_POST['tel']); ?></div>
    </div>
    
    <?php if(!empty($_POST['formacao'])): ?>
        <h2>Formação</h2>
        <p><?php echo trim($_POST['formacao']); ?></p>
    <?php endif; ?>

    <?php if(!empty($_POST['idioma'])): ?>
        <h2>Idiomas</h2>
        <ul>
        <?php 
        $idiomas = array_combine($_POST['idioma'], $_POST['nivel_idioma']);
        foreach($idiomas as $idioma => $nivel): ?>

            <li><?php echo trim($idioma) . ' - ' . trim($nivel); ?></li>

        <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <?php if(!empty($_POST['curso'][0])): ?>
        <h2>Cursos</h2>
        <ul>
        <?php 
        $dadosCurso = array_combine($_POST['curso'], $_POST['local_curso']);
        foreach($dadosCurso as $curso => $local): ?>

            <li><?php echo trim($curso) . ' - ' . trim($local); ?></li>
            
        <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <?php if(!empty($_POST['conhecimentos'])): ?>
        <h2>Conhecimentos</h2>
        <p><?php echo nl2br(trim($_POST['conhecimentos'])); ?></p>
    <?php endif; ?>

    <?php if(!empty($_POST['empresa'][0])): ?>
        <h2>Experiência</h2>
        <ul class="experiencias">
        <?php for($i = 0; $i < count($_POST['empresa']); $i++): ?>

            <li <?php echo (count($_POST['empresa']) === ($i +1)) ? 'class="ultima-experiencia"' : ''; ?>>
                <div class="experiencia">
                    <strong><?php echo $_POST['funcao'][$i]; ?></strong>, <?php echo $_POST['empresa'][$i]; ?><br />
                    <div class="data-atuacao">De <?php echo $_POST['mes_entrada'][$i]; ?> até <?php echo ($_POST['trabalho_atual'][$i]) ? 'o momento' : $_POST['mes_saida'][$i]; ?></div>
                    <div class="descricao-exp"><?php echo nl2br(trim($_POST['descricao_exp'][$i])); ?></div>
                </div>
            </li>

        <?php endfor; ?>
        </ul>
    <?php endif; ?>

    <?php if(!empty($_POST['projeto'][0])): ?>
        <h2>Projetos</h2>
        <ul class="projetos">
        <?php for($i = 0; $i < count($_POST['projeto']); $i++): ?>

            <li>
                <div class="projeto">
                    <strong><?php echo trim($_POST['projeto'][$i]); ?></strong>
                    <p><?php echo nl2br(trim($_POST['descricao_projeto'][$i])); ?></p>
                    <p class="url">Link: <?php echo trim($_POST['url_projeto'][$i]); ?></p>
                </div>
            </li>

        <?php endfor; ?>
        </ul>
    <?php endif; ?>
</div>

<?php
$html = ob_get_contents();
ob_end_clean();

$tipoCurriculo = ($_POST['tipo_curriculo'] === 'download') ? \Mpdf\Output\Destination::DOWNLOAD : \Mpdf\Output\Destination::INLINE;

$style = file_get_contents('css/curriculo.css');

$mpdf->SetTitle($nome);
$mpdf->SetAuthor($nome);
$mpdf->WriteHTML($style, \Mpdf\HTMLParserMode::HEADER_CSS);
$mpdf->WriteHTML($html, \Mpdf\HTMLParserMode::HTML_BODY);
$mpdf->Output('Currículo - ' . $nome . '.pdf', $tipoCurriculo);