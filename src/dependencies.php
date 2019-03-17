<?php
// DIC configuration

$container = $app->getContainer();

// view renderer
$container['renderer'] = function ($c) {
    $settings = $c->get('settings')['renderer'];
    return new Slim\Views\PhpRenderer($settings['template_path']);
};

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};

$container['db'] = function ($c) {
    $db = $c->get('settings')['db'];
    $fsql = new FSQL\Environment();
    $fsql->define_db($db['dbname'], $db['path']);
    $fsql->select_db($db['dbname']);

    $fsql->query("CREATE TABLE IF NOT EXISTS posts(id INT AUTO_INCREMENT PRIMARY KEY, title TEXT NOT NULL, createdTime TEXT NOT NULL, content TEXT)");
    $fsql->query("CREATE TABLE IF NOT EXISTS comments(id INT AUTO_INCREMENT PRIMARY KEY, name TEXT, createdTime TEXT, post_id INT, content TEXT);");

    $fsql->query("TRUNCATE TABLE posts RESTART IDENTITY");
    $fsql->query("TRUNCATE TABLE comments RESTART IDENTITY");

    $insert = $fsql->prepare("INSERT INTO posts(title, createdTime, content) VALUES (?, ?, ?);");

    $title = "Welcome to my blog";
    $createdTime = "2018-11-26T02:52:40+00:00";
    $content = "Thanks for joining me on my new blog.\nI primarily use it for site news and updates (such as new release or new content added).\nI’m playing around with moving the fSQL docs back to the new site layout now that I’ve gotten rid of Jekyll.";

    $insert->bind_param("sss", $title, $createdTime, $content);
    $insert->execute();

    $title = "Old Design Retired";
    $createdTime = "2019-03-08T21:01:46+00:00";
    $content = "I just switched from my sbuberl.com’s old Jekyll-based web design to the current design I was playing around with for a little while in a separate GitHub repo.\nPlus I can customize the syntax highlighting to any style I wish now.";
    $insert->execute();

    $title = "Adding Photo Albums";
    $createdTime = "2019-03-10T20:53:07+00:00";
    $content = "I started added some old photo albums of previous trips and activities to the site.\nI will be posting more soon.";
    $insert->execute();

    return $fsql;
};
