<?php

/**
 * xtlsoft.blog
 * 
 * @author Tianle Xu <xtl@xtlsoft.top>
 * @license MIT
 */

require_once "vendor/autoload.php";

use League\CommonMark\CommonMarkConverter;
use League\CommonMark\Environment;
use League\CommonMark\Extras\CommonMarkExtrasExtension;
use Symfony\Component\Yaml\Yaml;
use Webuni\CommonMark\TableExtension\TableExtension;

$environment = Environment::createCommonMarkEnvironment();
$environment->addExtension(new CommonMarkExtrasExtension());
$environment->addExtension(new TableExtension());
$converter = new CommonMarkConverter([], $environment);

echo shell_exec("mkdir -p dist && cd dist && git init && cd ..");
echo shell_exec("cp ./static/* ./dist -rvf");

function parsePageFile($name)
{
    global $converter;
    $content = file_get_contents($name);
    $content = explode("\n", $content);
    if (trim($content[0]) !== "```yaml") {
        echo "Build failed: $name has a mistake.\r\n";
        exit(-1);
    }
    $cnf = "";
    $php = "";
    $para = "";
    $flag = 0;
    foreach ($content as $k => $v) {
        if ($k === 0) continue;
        if ($flag === 1) {
            $para .= $v . "\n";
            continue;
        }
        if (trim($v) === "```") {
            if (trim(@$content[$k + 2]) === "```php") $flag = 2;
            else $flag = 1;
        } else if (!$flag) {
            $cnf .= $v . "\n";
        } else if (trim($v) !== "```php") {
            $php .= $v . "\n";
        }
    }
    if ($php === "") $php = "return [[]];";
    $var_lists = eval($php);
    foreach ($var_lists as $var_list) {
        $cnf2 = $cnf;
        foreach ($var_list as $key => $val) {
            $cnf2 = str_replace("\${" . $key . "}", $val, $cnf2);
        }
        $conf = Yaml::parse($cnf2);
        $para = $converter->convertToHtml($para);
        $conf["content"] = $para;
        $vars = $conf;
        $vars = $vars;
        @ob_start();
        include "./template/" . $conf['template'];
        $r = @ob_get_clean();
        system("mkdir -p ./dist/" . dirname($conf['route']));
        file_put_contents("./dist/" . $conf['route'], $r);
    }
    echo "Finished parsing: $name\r\n";
}

function parseMarkdown(string $content): string
{
    global $converter;
    return $converter->convertToHtml($content);
}

$categories = Yaml::parse(file_get_contents("./categories.yaml"));

$articles = [];
$count_articles = 0;

foreach ($categories as $k => $category_name) {
    $articles[$k] = [];
    foreach (glob("./articles/$k/*") as $v) {
        $article = explode("\n", file_get_contents($v));
        $title = trim(substr($article[0], 1));
        // Information string starts with "% "
        // on line 3 (line 2 should be empty)
        // and contains author and time.
        // Like: % xtlsoft, 2004-09-29 00:00:00
        $informations = explode(", ", trim(substr($article[2], 1)));
        $content = parseMarkdown(implode("\n", array_slice($article, 3)));
        $author = trim($informations[0]);
        $time = date_timestamp_get(
            date_create_from_format('Y-m-d H:i:s', trim($informations[1]))
        );
        $id = substr(basename($v), 0, -3);
        $articles[$k][] = [
            "id" => $id,
            "title" => $title,
            "category" => $category_name,
            "content" => $content,
            "category_id" => $k,
            "time" => $time,
            "author" => $author
        ];
        ++$count_articles;
        echo "Finished parsing: $v\r\n";
    }
}

function getArticleNumber(): int
{
    global $count_articles;
    return $count_articles;
}

foreach (glob("./pages/*") as $v) {
    parsePageFile($v);
}
