<html>
<head>
    <link rel="stylesheet" href="style.css">
    <script src="https://code.jquery.com/jquery-2.2.1.min.js"></script>
    <script src="script.js"></script>
</head>
<body>

<form method="post">
    <label for="file">File:</label>
    <select name="file" id="file">
        <?php
        $dir = ini_get('xdebug.trace_output_dir');
        if(!$dir) $dir = '/tmp/';
        $files = glob("$dir/*.xt");
        foreach($files as $file) {
            echo '<option value="'.htmlspecialchars($file).'">'.htmlspecialchars(basename($file)).'</option>';
        }
        ?>
    </select>
    <button type="submit">Load</button>
</form>


<form>
    <input type="checkbox" value="1" checked="checked" id="internal"><label for="internal">Show internal functions</label>
</form>


<?php


if(!empty($_REQUEST['file'])) {
    require_once 'XDebugParser.php';
    $parser = new XDebugParser($_REQUEST['file']);
    $parser->parse();
    echo $parser->getTraceHTML();
}
?>

</body>
</html>
