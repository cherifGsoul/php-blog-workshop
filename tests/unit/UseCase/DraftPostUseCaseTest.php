<?php


namespace BlogTest\Unit\UseCase;


use Blog\Model\Author;
use Blog\Model\Post;
use Blog\Model\Posts;
use Blog\Service\AuthorService;
use Blog\Service\PostService;
use Blog\UseCase\Command\DraftPostCommand;
use PHPUnit\Framework\TestCase;
use Blog\UseCase\DraftPostUseCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;

class DraftPostUseCaseTest extends TestCase
{
    use ProphecyTrait;

    private DraftPostUseCase $useCase;

    /** @test */
    public function it_drafts_a_blog_post_with_valid_data()
    {
        $auhtor = Author::named('cherif', 'Bouchelaghem');

        $posts = $this->prophesize(Posts::class);
        $authorService = $this->prophesize(AuthorService::class);

        $posts->save(Argument::type(Post::class))->shouldBeCalled();
        $authorService->fromUsername('cherif')->willReturn($auhtor);

        $this->useCase = new DraftPostUseCase($posts->reveal(), $authorService->reveal());
        $useCase = $this->useCase;
        $draftPostCommand = new DraftPostCommand('PHP from scratch', 'Body of the blog post', ['php', 'programming'], 'cherif');
        $result = $useCase($draftPostCommand);
        $this->assertTrue($result);
    }

    /** @test */
    public function it_throws_when_the_author_doesnt_exist()
    {

        $this->expectException(\DomainException::class);
        $posts = $this->prophesize(Posts::class);
        $authorService = $this->prophesize(AuthorService::class);

        $authorService->fromUsername('cherif')->willThrow(\InvalidArgumentException::class);

        $this->useCase = new DraftPostUseCase($posts->reveal(), $authorService->reveal());
        $useCase = $this->useCase;
        $draftPostCommand = new DraftPostCommand('PHP from scratch', 'Body of the blog post', ['php', 'programming'], 'cherif');
        $useCase($draftPostCommand);
    }

    /** @test */
    public function it_throws_when_theres_an_error_on_persistence()
    {
        $auhtor = Author::named('cherif', 'Bouchelaghem');
        $this->expectException(\DomainException::class);
        $posts = $this->prophesize(Posts::class);
        $authorService = $this->prophesize(AuthorService::class);

        $authorService->fromUsername('cherif')->willReturn($auhtor);
        $posts->save(Argument::type(Post::class))->willThrow(\InvalidArgumentException::class);

        $this->useCase = new DraftPostUseCase($posts->reveal(), $authorService->reveal());
        $useCase = $this->useCase;
        $draftPostCommand = new DraftPostCommand('PHP from scratch', 'Body of the blog post', ['php', 'programming'], 'cherif');
        $useCase($draftPostCommand);
    }
}