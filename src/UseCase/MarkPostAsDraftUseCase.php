<?php


namespace Blog\UseCase;



use Blog\Model\Posts;

class MarkPostAsDraftUseCase implements UseCase
{
    private Posts $posts;

    public function __construct(Posts $posts)
    {
        $this->posts = $posts;
    }

    public function __invoke(object $command)
    {
        $post = $this->posts->forTitle($command->title);
        $post->markAsDraft();

        $this->posts->save($post);
        return true;
    }
}