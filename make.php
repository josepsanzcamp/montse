<?php

$html = file_get_contents("Glossari_Signes.html");
$html = explode("\n", $html);
//~ print_r($html);die();

$data = file_get_contents("edu365-signes/data.json");
$data = json_decode($data, true);
$data = $data["paraules"];
//~ print_r($data);die();

$paraules = array_column($data, "paraula");
//~ print_r($paraules);die();

$wiki = [];
$files = glob("viktionary/index.php*");
foreach ($files as $file) {
    $temp = file_get_contents($file);
    $temp = str_replace("<ul><li>", "<ul>\n<li>", $temp);
    $temp = str_replace("</li></ul>", "</li>\n</ul>", $temp);
    $temp = str_replace("</li><li>", "</li>\n<li>", $temp);
    $temp = explode("\n", $temp);
    foreach ($temp as $key => $val) {
        if (strpos($val, "/wiki/Categoria:") !== false) {
            continue;
        }
        if (substr($val, 0, 7) != "<li><a ") {
            continue;
        }
        $wiki[] = strip_tags($val);
    }
}
//~ print_r($wiki);die();

foreach ($html as $key => $val) {
    $val = trim($val);
    if (substr($val, 0, 4) != "<td ") {
        continue;
    }
    $paraula = strip_tags($val);
    if ($paraula == "") {
        continue;
    }
    $paraula2 = mb_strtoupper($paraula);
    if (!in_array($paraula2, $paraules)) {
        continue;
    }
    $link = "<a href='https://www.edu365.cat/signes/app/index.html?paraula=$paraula2' target='_blank'>$paraula</a>";
    $val = str_replace($paraula, $link, $val);
    $html[$key] = $val;
}

foreach ($html as $key => $val) {
    if (strpos($val, "www.edu365.cat") !== false) {
        continue;
    }
    $val = trim($val);
    if (substr($val, 0, 4) != "<td ") {
        continue;
    }
    $paraula = strip_tags($val);
    if ($paraula == "") {
        continue;
    }
    $paraula2 = mb_strtoupper($paraula);
    if (!in_array($paraula2, $wiki)) {
        continue;
    }
    $link = "<a href='https://ca.wiktionary.org/wiki/$paraula2' target='_blank'>$paraula</a>";
    $val = str_replace($paraula, $link, $val);
    $html[$key] = $val;
}

$html = implode("\n", $html);
file_put_contents("index.html", $html);
