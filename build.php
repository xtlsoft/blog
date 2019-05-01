<?php

require_once "vendor/autoload.php";

use League\CommonMark\CommonMarkConverter;
use League\CommonMark\Environment;
use League\CommonMark\Extras\CommonMarkExtrasExtension;
use Symfony\Component\Yaml\Yaml;

$environment = Environment::createCommonMarkEnvironment();
$environment->addExtension(new CommonMarkExtrasExtension());
$converter = new CommonMarkConverter([], $environment);

echo shell_exec("mkdir dist && cd dist && git init && cd ..");

function parseFile($name) {

    global $converter;

    if (is_dir($name)) {
        mkdir("./dist/" . substr($name, 10));
        foreach (glob($name . "/*") as $v) {
            parseFile($v);
        }
        return;
    }

    $job = substr($name, 10, -3);

    if (substr($name, -3) !== ".md") {
        echo shell_exec("cp -v $name ./dist/$job" . substr($name, -3));
        return;
    }

    $content = file_get_contents($name);
    $content = explode("\n", $content);
    if (trim($content[0]) !== "```yaml") {
        echo "Build failed: $name has a mistake.\r\n";
        exit(-1);
    }
    $conf = "";
    $para = "";
    $flag = false;
    foreach ($content as $k=>$v) {
        if ($k === 0) continue;
        if ($flag) {
            $para .= $v . "\n";
            continue;
        }
        if (trim($v) === "```") {
            $flag = true;
        } else {
            $conf .= $v . "\n";
        }
    }

    $conf = Yaml::parse($conf);
    $para = $converter->convertToHtml($para);
    $conf["content"] = $para;

    $tpl = file_get_contents("./template/" . $conf['template']);

    foreach ($conf as $k=>$v) {
        $tpl = str_replace('${'.$k.'}', $v, $tpl);
    }

    file_put_contents("./dist/" . $job . ".html", $tpl);
    echo "Finished parsing: $name\r\n";
    
}

foreach (glob("./content/*") as $v) {
    parseFile($v);
}