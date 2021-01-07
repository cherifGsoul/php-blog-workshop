<?php


namespace Blog\UseCase\Command;


class DraftPostCommand
{
    public string $title;
    public string $body;
    public array $tags;
    public string $author;

    public function __construct(string $title, string $body, array $tags, string$author)
    {
        $this->title = $title;
        $this->body = $body;
        $this->tags = $tags;
        $this->author = $author;
    }
}