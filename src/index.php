<?php
$dir = '' . dirname(__DIR__) . '/functions/';
$dir = dir($dir);
while ($arquivo = $dir->read()) {
    if ($arquivo == ".." || $arquivo == ".") {
    } else {
        include '' . dirname(__DIR__) . '/functions/' . $arquivo . '';
    }
}
$dir->close();
?>