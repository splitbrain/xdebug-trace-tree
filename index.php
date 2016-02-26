<!doctype html>
<html>
<head>
    <title>XDebug Trace Tree</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://code.jquery.com/jquery-2.2.1.min.js"></script>
    <script src="script.js"></script>
</head>
<body>

<form method="post" class="load">
    <label for="file">File:</label>
    <select name="file" id="file">
        <?php
        $dir = ini_get('xdebug.trace_output_dir');
        if (!$dir) {
            $dir = '/tmp/';
        }
        $files = glob("$dir/*.xt");
        foreach ($files as $file) {
            echo '<option value="' . htmlspecialchars($file) . '">' . htmlspecialchars(basename($file)) . '</option>';
        }
        ?>
    </select>
    <button type="submit">Load</button><br />
    Files are read from xdebug.trace_output_dir = <?php echo htmlspecialchars($dir)?>
</form>

<ul class="help">
    <li>load a trace file from the dropdown</li>
    <li>click a left margin to collapse a whole sub tree</li>
    <li>click a function name to collapse all calls to the same function</li>
    <li>click the parameter list to expand it</li>
    <li>click the return list to expand it</li>
    <li>use the checkbox to hide all PHP internal functions</li>
</ul>

<form class="options">
    <input type="checkbox" value="1" checked="checked" id="internal"><label for="internal">Show internal
        functions</label>
</form>


<?php

if (!empty($_REQUEST['file'])) {
    require_once 'XDebugParser.php';
    $parser = new XDebugParser($_REQUEST['file']);
    $parser->parse();
    echo $parser->getTraceHTML();
}
?>

</body>
</html>
