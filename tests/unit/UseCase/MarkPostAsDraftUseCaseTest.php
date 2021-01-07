<?php


namespace BlogTest\Unit\UseCase;


use Blog\Model\Author;
use Blog\Model\Post;
use Blog\Model\Posts;
use Blog\UseCase\Command\MarkPostAsDraftCommand;
use Blog\UseCase\MarkPostAsDraftUseCase;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;

class MarkPostAsDraftUseCaseTest extends TestCase
{
    use ProphecyTrait;

    /** @test */
    public function it_mark_a_published_post_as_draft()
    {
        $title = 'PHP App from Scratch';
        $body = 'The body of the blog post';
        $tags = ['php', 'db', 'code'];
        $author = Author::named('cherif', 'bouchelaghem');
        $post = Post::publish($title, $body, $tags, $author);

        $posts = $this->prophesize(Posts::class);

        $command = new MarkPostAsDraftCommand('PHP App from Scratch');

        $posts->forTitle($command->title)->shouldBeCalled();
        $posts->forTitle($command->title)->willReturn($post);

        $posts->save($post)->willReturn(true);

        $useCase = new MarkPostAsDraftUseCase($posts->reveal());

        $this->assertTrue($useCase($command));

        $posts->save($post)->shouldHaveBeenCalledOnce();

    }
}