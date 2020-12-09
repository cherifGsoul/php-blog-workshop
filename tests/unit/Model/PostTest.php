<?php

namespace BlogTest\Unit\Model;

use Blog\Model\Author;
use PHPUnit\Framework\TestCase;
use Blog\Model\Post;
use DateTimeImmutable;
use DomainException;
use InvalidArgumentException;

class PostTest extends TestCase
{
	public function testTheAuthorPublishAPost()
	{
		$title = 'PHP App from Scratch';
		$body = 'The body of the blog post';
		$tags = ['php', 'db', 'code'];
		$author = Author::named('cherif', 'bouchelaghem');
		$post = Post::publish($title, $body, $tags, $author);
		$this->assertTrue($post->isPublished());
	}

	public function testTheAuthorDraftAPost()
	{
		$title = 'PHP App from Scratch';
		$body = 'The body of the blog post';
		$tags = ['php', 'db', 'code'];
		$author = Author::named('cherif', 'bouchelaghem');
		$post = Post::draft($title, $body, $tags, $author);
		$this->assertTrue($post->isDraft());
	}

	public function testTitleMustNotBeEmpty()
	{
		$this->expectException(InvalidArgumentException::class);
		$title = '';
		$body = 'The body of the blog post';
		$tags = ['php', 'db', 'code'];
		$author = Author::named('cherif', 'bouchelaghem');
		Post::draft($title, $body, $tags, $author);
	}

	public function testTitleLengthMustNotBeMoreThan250chars()
	{
		$this->expectException(InvalidArgumentException::class);
		$title = 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Qui ipsum, beatae, quam fuga maiores rem delectus ratione nesciunt cum numquam ipsam eius velit dolorum inventore tenetur. Consequatur nam deserunt accusantium. Lorem ipsum dolor sit amet consectetur adipisicing elit. Qui ipsum, beatae, quam fuga maiores rem delectus ratione nesciunt cum numquam ipsam eius velit dolorum inventore tenetur. Consequatur nam deserunt accusantium.';
		$body = 'The body of the blog post';
		$tags = ['php', 'db', 'code'];
		$author = Author::named('cherif', 'bouchelaghem');
		Post::draft($title, $body, $tags, $author);
	}

	public function testBodyMustNotBeEmpty()
	{
		$this->expectException(InvalidArgumentException::class);
		$title = 'lorem ipsum';
		$body = '';
		$tags = ['php', 'db', 'code'];
		$author = Author::named('cherif', 'bouchelaghem');
		Post::draft($title, $body, $tags, $author);
	}

	public function testMarkPostAsDraft()
	{
		$title = 'PHP App from Scratch';
		$body = 'The body of the blog post';
		$tags = ['php', 'db', 'code'];
		$author = Author::named('cherif', 'bouchelaghem');
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
		$author = Author::named('cherif', 'bouchelaghem');
		$post = Post::draft($title, $body, $tags, $author);
		$post->markAsDraft();
	}

	public function testMarkPostAsPublish()
	{
		$title = 'PHP App from Scratch';
		$body = 'The body of the blog post';
		$tags = ['php', 'db', 'code'];
		$author = Author::named('cherif', 'bouchelaghem');
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
		$author = Author::named('cherif', 'bouchelaghem');
		$post = Post::publish($title, $body, $tags, $author);
		$post->markAspublish();
	}

	public function testChangeTitle()
	{
		$title = 'PHP App from Scratch';
		$body = 'The body of the blog post';
		$tags = ['php', 'db', 'code'];
		$author = Author::named('cherif', 'bouchelaghem');
		$post = Post::publish($title, $body, $tags, $author);
		$post->changeTitle('Another changed title', $author);
		$this->assertEquals('Another changed title', $post->getTitle());
	}

	public function testEditBody()
	{
		$title = 'PHP App from Scratch';
		$body = 'The body of the blog post';
		$tags = ['php', 'db', 'code'];
		$author = Author::named('cherif', 'bouchelaghem');
		$post = Post::publish($title, $body, $tags, $author);
		$post->editBody('The edited body here', $author);
		$this->assertEquals('The edited body here', $post->getBody());
	}

	public function testOnlyPostAuthorCanChangeTheTitle()
	{
		$this->expectException(DomainException::class);
		$title = 'PHP App from Scratch';
		$body = 'The body of the blog post';
		$tags = ['php', 'db', 'code'];
		$author = Author::named('cherif', 'bouchelaghem');
		$post = Post::publish($title, $body, $tags, $author);
		$post->changeTitle('Another title', Author::named('foo', 'bar'));
	}

	public function testOnlyPostAuthorCanEditBody()
	{
		$this->expectException(DomainException::class);
		$title = 'PHP App from Scratch';
		$body = 'The body of the blog post';
		$tags = ['php', 'db', 'code'];
		$author = Author::named('cherif', 'bouchelaghem');
		$post = Post::publish($title, $body, $tags, $author);
		$post->editBody('Foo bar baz',  Author::named('foo', 'bar'));
	}

	public function testIsWrittenByReturnsTrueForTheSameAuthor()
	{
		$title = 'PHP App from Scratch';
		$body = 'The body of the blog post';
		$tags = ['php', 'db', 'code'];
		$author = Author::named('cherif', 'bouchelaghem');
		$post = Post::publish($title, $body, $tags, $author);
		$this->assertTrue($post->isWrittentBy($author));
	}


	public function testAddTag()
	{
		$title = 'PHP App from Scratch';
		$body = 'The body of the blog post';
		$tags = ['php'];
		$author = Author::named('cherif', 'bouchelaghem');
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
		$author = Author::named('cherif', 'bouchelaghem');
		$post = Post::publish($title, $body, $tags, $author);
		$post->addTag('programming');
	}

	public function testRemoveTag()
	{
		$title = 'PHP App from Scratch';
		$body = 'The body of the blog post';
		$tags = ['php'];
		$author = Author::named('cherif', 'bouchelaghem');
		$post = Post::publish($title, $body, $tags, $author);
		$post->removeTag('programming');
		$this->assertFalse($post->hasTag('programming'));
	}

	public function testSetCreatedAtOnIntialPublishing()
	{
		$title = 'PHP App from Scratch';
		$body = 'The body of the blog post';
		$tags = ['php'];
		$author = Author::named('cherif', 'bouchelaghem');
		$post = Post::publish($title, $body, $tags, $author);
		$this->assertInstanceOf(DateTimeImmutable::class, $post->getCreatedAt());
		$this->assertEquals('09/12/2020', $post->getCreatedAt()->format('d/m/Y'));
	}

	public function testSetCreatedAtOnIntialDrafting()
	{
		$title = 'PHP App from Scratch';
		$body = 'The body of the blog post';
		$tags = ['php'];
		$author = Author::named('cherif', 'bouchelaghem');
		$post = Post::draft($title, $body, $tags, $author);
		$this->assertInstanceOf(DateTimeImmutable::class, $post->getCreatedAt());
		$this->assertEquals('09/12/2020', $post->getCreatedAt()->format('d/m/Y'));
	}

	public function testSetUpdatedMarkAsDraft()
	{
		$title = 'PHP App from Scratch';
		$body = 'The body of the blog post';
		$tags = ['php'];
		$author = Author::named('cherif', 'bouchelaghem');
		$post = Post::publish($title, $body, $tags, $author);
		$post->markAsDraft();
		$this->assertInstanceOf(DateTimeImmutable::class, $post->getUpdatedAt());
	}

	public function testSetUpdatedMarkAsPublish()
	{
		$title = 'PHP App from Scratch';
		$body = 'The body of the blog post';
		$tags = ['php'];
		$author = Author::named('cherif', 'bouchelaghem');
		$post = Post::draft($title, $body, $tags, $author);
		$post->markAsPublish();
		$this->assertInstanceOf(DateTimeImmutable::class, $post->getUpdatedAt());
	}

}