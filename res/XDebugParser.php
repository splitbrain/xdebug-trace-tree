<?php

class XDebugParser
{

    protected $handle;

    protected $functions = array();

    public function __construct($fileName)
    {
        $this->handle = fopen($fileName, 'r');
        if (!$this->handle) {
            throw new Exception("Can't open '$fileName'");
        }
        $header1 = fgets($this->handle);
        $header2 = fgets($this->handle);
        if (!preg_match('@Version: [23].*@', $header1) || !preg_match('@File format: [2-4]@', $header2)) {
            throw new Exception("This file is not an Xdebug trace file made with format option '1' and version 2 to 4.");
        }
    }

    public function parse()
    {
        $c = 0;
        $size = fstat($this->handle);
        $size = $size['size'];
        $read = 0;

        while (!feof($this->handle)) {
            $buffer = fgets($this->handle, 4096);
            $read += strlen($buffer);
            $this->parseLine($buffer);
            $c++;
        }
    }

    function parseLine($line)
    {
        $parts = explode("\t", $line);
        if (count($parts) < 5) {
            return;
        }

        $funcNr = (int) $parts[1];
        $type = $parts[2];

        switch ($type) {
            case '0': // Function enter
                $this->functions[$funcNr] = array();
                $this->functions[$funcNr]['depth'] = (int) $parts[0];
                $this->functions[$funcNr]['time.enter'] = $parts[3];
                $this->functions[$funcNr]['memory.enter'] = $parts[4];
                $this->functions[$funcNr]['name'] = $parts[5];
                $this->functions[$funcNr]['internal'] = !(bool) $parts[6];
                $this->functions[$funcNr]['file'] = $parts[8];
                $this->functions[$funcNr]['line'] = $parts[9];
                if ($parts[7]) {
                    $this->functions[$funcNr]['params'] = array($parts[7]);
                } else {
                    $this->functions[$funcNr]['params'] = array_slice($parts, 11);
                }

                // these are set later
                $this->functions[$funcNr]['time.exit'] = '';
                $this->functions[$funcNr]['memory.exit'] = '';
                $this->functions[$funcNr]['time.diff'] = '';
                $this->functions[$funcNr]['memory.diff'] = '';
                $this->functions[$funcNr]['return'] = '';
                break;
            case '1': // Function exit
                $this->functions[$funcNr]['time.exit'] = $parts[3];
                $this->functions[$funcNr]['memory.exit'] = $parts[4];
                $this->functions[$funcNr]['time.diff'] = $this->functions[$funcNr]['time.exit'] - $this->functions[$funcNr]['time.enter'];
                $this->functions[$funcNr]['memory.diff'] = (int) $this->functions[$funcNr]['memory.exit'] - (int) $this->functions[$funcNr]['memory.enter'];
                break;
            case 'R'; // Function return
                $this->functions[$funcNr]['return'] = $parts[5];
                break;
        }
    }

    function getTrace()
    {
        return $this->functions;
    }

    function getTraceHTML()
    {
        ob_start();

        echo '<div class="f header">';
        echo '<div class="func">Function Call</div>';
        echo '<div class="data">';
        echo '<span class="file">File:Line</span>';
        echo '<span class="timediff">ΔTime</span>';
        echo '<span class="memorydiff">ΔMemory</span>';
        echo '<span class="time">Time</span>';
        echo '</div>';
        echo '</div>';

        $level = 0;
        foreach ($this->functions as $func) {
            // depth wrapper
            if ($func['depth'] > $level) {
                for ($i = $level; $i < $func['depth']; $i++) {
                    echo '<div class="d">';
                }
            } elseif ($func['depth'] < $level) {
                for ($i = $func['depth']; $i < $level; $i++) {
                    echo '</div>';
                }
            }
            $level = $func['depth'];

            $class = 'f';
            if ($func['internal']) {
                $class .= ' i';
            }

            echo '<div class="' . $class . '">';

            echo '<div class="func">';
            echo '<span class="name">' . htmlspecialchars($func['name']) . '</span>';
            echo '(<span class="params short">' . htmlspecialchars(join(",", $func['params'])) . '</span>) ';
            if ($func['return'] !== '') {
                echo '→ <span class="return short">' . htmlspecialchars($func['return']) . '</span>';
            }
            echo '</div>';

            echo '<div class="data">';
            echo '<span class="file" title="'.htmlspecialchars($func['file'].':'.$func['line']).'">'.htmlspecialchars(basename($func['file']).':'.$func['line']).'</span>';
            echo '<span class="timediff">' . sprintf('%f', $func['time.diff']) . '</span>';
            echo '<span class="memorydiff">' . sprintf('%d', $func['memory.diff']) . '</span>';
            echo '<span class="time">' . sprintf('%f', $func['time.enter']) . '</span>';
            echo '</div>';

            echo '</div>';
        }

        if ($level > 0) {
            for ($i = 0; $i < $level; $i++) {
                echo '</div>';
            }
        }

        $html = ob_get_contents();
        ob_end_clean();
        return $html;
    }

}