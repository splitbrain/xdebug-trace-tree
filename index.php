<!doctype html>
<html>
<head>
    <title>XDebug Trace Tree</title>
    <link rel="stylesheet" href="res/style.css">
    <script src="https://code.jquery.com/jquery-2.2.1.min.js"></script>
    <script src="res/script.js"></script>
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
    <button type="submit">Load</button>
    <br/>

    <p>Files are read from <code>xdebug.trace_output_dir = <?php echo htmlspecialchars($dir) ?></code></p>
</form>

<ul class="help">
    <li>load a trace file from the dropdown</li>
    <li>click a left margin to collapse a whole sub tree</li>
    <li>click a function name to collapse all calls to the same function</li>
    <li>click the parameter list to expand it</li>
    <li>click the return list to expand it</li>
    <li>click the time to mark the line important</li>
    <li>use checkboxes to hide all PHP internal functions or limit to important lines</li>
</ul>

<form class="options">
    <input type="checkbox" value="1" checked="checked" id="internal">
    <label for="internal">Show internal functions</label>

    <input type="checkbox" value="1" id="marked">
    <label for="marked">Show important only (slow)</label>
</form>


<?php

if (!empty($_REQUEST['file'])) {
    require_once 'res/XDebugParser.php';
    $parser = new XDebugParser($_REQUEST['file']);
    $parser->parse();
    echo $parser->getTraceHTML();
}
?>


<a href="https://github.com/splitbrain/xdebug-trace-tree">
    <img style="position: absolute; top: 0; right: 0; border: 0;"
         src="https://camo.githubusercontent.com/a6677b08c955af8400f44c6298f40e7d19cc5b2d/68747470733a2f2f73332e616d617a6f6e6177732e636f6d2f6769746875622f726962626f6e732f666f726b6d655f72696768745f677261795f3664366436642e706e67"
         alt="Fork me on GitHub"
         data-canonical-src="https://s3.amazonaws.com/github/ribbons/forkme_right_gray_6d6d6d.png">
</a>

</body>
</html>
