<?php


namespace Blog\UseCase;


use Blog\Model\Post;
use Blog\Model\Posts;
use Blog\Service\AuthorService;
use Blog\UseCase\Command\DraftPostCommand;

class PublishPostUseCase implements UseCase
{
    private Posts $posts;
    private AuthorService $authorService;

    public function __construct(Posts $posts, AuthorService $authorService)
    {
        $this->posts = $posts;
        $this->authorService = $authorService;
    }

    /**
     * @param object|DraftPostCommand $command
     */
    public function __invoke(object $command)
    {
        try {
            $author = $this->authorService->fromUsername($command->author);
        } catch (\Exception $e) {
            throw new \DomainException('There\'s an author problem' );
        }

        $post = Post::publish(
            $command->title,
            $command->body,
            $command->tags,
            $author
        );

        try {
            $this->posts->save($post);
        } catch (\Exception $e) {
            throw new \DomainException('Can not save the drafted post');
        }

        return true;
    }
}