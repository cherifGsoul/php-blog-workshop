<?php

namespace BlogTest\Unit\Model;

use Blog\Model\Author;
use Blog\Model\Post;
use Blog\Model\Posts;
use Blog\Service\AuthorService;
use Blog\Service\PostService;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;

class PostServiceTest extends TestCase
{
	use ProphecyTrait;

	/** @test */
	public function it_drafts_a_blog_post_with_valid_parameters()
	{
		$auhtor = Author::named('cherif', 'Bouchelaghem');

		$posts = $this->prophesize(Posts::class);
		$authorService = $this->prophesize(AuthorService::class);

		$posts->save(Argument::type(Post::class))->shouldBeCalled();
		$authorService->fromUsername('cherif')->willReturn($auhtor);

		$postService = new PostService($posts->reveal(), $authorService->reveal());

		$result = $postService->draft('PHP from scratch', 'Body of the blog post', ['php', 'programming'], 'cherif');
		$this->assertTrue($result);
	}
}