<?php


namespace Blog\UseCase\Command;


class MarkPostAsDraftCommand
{
    public string $title;

    public function __construct(string $title)
    {
        $this->title = $title;
    }
}