<?php

namespace BlogTest\Unit;

use PHPUnit\Framework\TestCase;
use Blog\Model\Post;
use DomainException;
use InvalidArgumentException;

class PostTest extends TestCase
{
	public function testTheAuthorPublishAPost()
	{
		$title = 'PHP App from Scratch';
		$body = 'The body of the blog post';
		$tags = ['php', 'db', 'code'];
		$author = 'cherif';
		$post = Post::publish($title, $body, $tags, $author);
		$this->assertTrue($post->isPublished());
	}

	public function testTheAuthorDraftAPost()
	{
		$title = 'PHP App from Scratch';
		$body = 'The body of the blog post';
		$tags = ['php', 'db', 'code'];
		$author = 'cherif';
		$post = Post::draft($title, $body, $tags, $author);
		$this->assertTrue($post->isDraft());
	}

	public function testTitleMustNotBeEmpty()
	{
		$this->expectException(InvalidArgumentException::class);
		$title = '';
		$body = 'The body of the blog post';
		$tags = ['php', 'db', 'code'];
		$author = 'cherif';
		Post::draft($title, $body, $tags, $author);
	}

	public function testTitleLengthMustNotBeMoreThan250chars()
	{
		$this->expectException(InvalidArgumentException::class);
		$title = 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Qui ipsum, beatae, quam fuga maiores rem delectus ratione nesciunt cum numquam ipsam eius velit dolorum inventore tenetur. Consequatur nam deserunt accusantium. Lorem ipsum dolor sit amet consectetur adipisicing elit. Qui ipsum, beatae, quam fuga maiores rem delectus ratione nesciunt cum numquam ipsam eius velit dolorum inventore tenetur. Consequatur nam deserunt accusantium.';
		$body = 'The body of the blog post';
		$tags = ['php', 'db', 'code'];
		$author = 'cherif';
		Post::draft($title, $body, $tags, $author);
	}

	public function testBodyMustNotBeEmpty()
	{
		$this->expectException(InvalidArgumentException::class);
		$title = 'lorem ipsum';
		$body = '';
		$tags = ['php', 'db', 'code'];
		$author = 'cherif';
		Post::draft($title, $body, $tags, $author);
	}

	public function testMarkPostAsDraft()
	{
		$title = 'PHP App from Scratch';
		$body = 'The body of the blog post';
		$tags = ['php', 'db', 'code'];
		$author = 'cherif';
		$post = Post::publish($title, $body, $tags, $author);
		$post->markAsDraft();
		$this->assertTrue($post->isDraft());
	}

	public function testMarkAsDraftThrowWhenPostIsAlreadyDraft()
	{
		$this->expectException(DomainException::class);
		$title = 'PHP App from Scratch';
		$body = 'The body of the blog post';
		$tags = ['php', 'db', 'code'];
		$author = 'cherif';
		$post = Post::draft($title, $body, $tags, $author);
		$post->markAsDraft();
	}

	public function testMarkPostAsPublish()
	{
		$title = 'PHP App from Scratch';
		$body = 'The body of the blog post';
		$tags = ['php', 'db', 'code'];
		$author = 'cherif';
		$post = Post::draft($title, $body, $tags, $author);
		$post->markAsPublish();
		$this->assertTrue($post->isPublished());
	}

	public function testMarkAsPublishThrowWhenPostIsAlreadyPublish()
	{
		$this->expectException(DomainException::class);
		$title = 'PHP App from Scratch';
		$body = 'The body of the blog post';
		$tags = ['php', 'db', 'code'];
		$author = 'cherif';
		$post = Post::publish($title, $body, $tags, $author);
		$post->markAspublish();
	}

	public function testChangeTitle()
	{
		$title = 'PHP App from Scratch';
		$body = 'The body of the blog post';
		$tags = ['php', 'db', 'code'];
		$author = 'cherif';
		$post = Post::publish($title, $body, $tags, $author);
		$post->changeTitle('Another changed title', 'cherif');
		$this->assertEquals('Another changed title', $post->getTitle());
	}

	public function testEditBody()
	{
		$title = 'PHP App from Scratch';
		$body = 'The body of the blog post';
		$tags = ['php', 'db', 'code'];
		$author = 'cherif';
		$post = Post::publish($title, $body, $tags, $author);
		$post->editBody('The edited body here', 'cherif');
		$this->assertEquals('The edited body here', $post->getBody());
	}

	public function testOnlyPostAuthorCanChangeTheTitle()
	{
		$this->expectException(DomainException::class);
		$title = 'PHP App from Scratch';
		$body = 'The body of the blog post';
		$tags = ['php', 'db', 'code'];
		$author = 'cherif';
		$post = Post::publish($title, $body, $tags, $author);
		$post->changeTitle('Another title', 'foo');
	}

	public function testOnlyPostAuthorCanEditBody()
	{
		$this->expectException(DomainException::class);
		$title = 'PHP App from Scratch';
		$body = 'The body of the blog post';
		$tags = ['php', 'db', 'code'];
		$author = 'cherif';
		$post = Post::publish($title, $body, $tags, $author);
		$post->editBody('Foo bar baz', 'foo');
	}

	public function testIsWrittenByReturnsTrueForTheSameAuthor()
	{
		$title = 'PHP App from Scratch';
		$body = 'The body of the blog post';
		$tags = ['php', 'db', 'code'];
		$author = 'cherif';
		$post = Post::publish($title, $body, $tags, $author);
		$this->assertTrue($post->isWrittentBy('cherif'));
	}


	public function testAddTag()
	{
		$title = 'PHP App from Scratch';
		$body = 'The body of the blog post';
		$tags = ['php'];
		$author = 'cherif';
		$post = Post::publish($title, $body, $tags, $author);
		$post->addTag('programming');
		$this->assertTrue($post->hasTag('programming'));
	}

	public function testThrowsWhenTryingToAddMoreThan4Tags()
	{
		$this->expectException(DomainException::class);
		$title = 'PHP App from Scratch';
		$body = 'The body of the blog post';
		$tags = ['php', 'db', 'code', 'TDD'];
		$author = 'cherif';
		$post = Post::publish($title, $body, $tags, $author);
		$post->addTag('programming');
	}

	public function testRemoveTag()
	{
		$title = 'PHP App from Scratch';
		$body = 'The body of the blog post';
		$tags = ['php','programming'];
		$author = 'cherif';
		$post = Post::publish($title, $body, $tags, $author);
		$post->removeTag('programming');
		$this->assertFalse($post->hasTag('programming'));
	}

}