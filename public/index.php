<?php

use Blog\Model\Post;

require '../vendor/autoload.php';


$post = new Post;

$post->setTitle('PHP app from scratch');

echo $post->getTitle();