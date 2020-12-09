<?php

namespace Blog\Model;

use DateTimeImmutable;
use DateTimeZone;
use DomainException;
use InvalidArgumentException;

class Post
{
	private $id;
	private string $title;
	private string $body;
	private array $tags;
	private string $status;
	private Author $author;
	private DateTimeImmutable $createdAt;
	private DateTimeImmutable $updatedAt;

	private function __construct(string $title, string $body, array $tags = [], Author $author, string $status)
	{
		$this->setTitle($title);
		$this->setBody($body);
		$this->tags = $tags;
		$this->author = $author;
		$this->status = $status;
	}
	
	public static function publish(string $title, string $body, array $tags = [], Author $author): Post
	{
		$post = new Post($title, $body, $tags, $author, 'publish');
		$post->setCreatedAt();
		return $post;
	}

	public static function draft(string $title, string $body, array $tags = [], Author $author): Post
	{
		$post = new Post($title, $body, $tags, $author, 'draft');
		$post->setCreatedAt();
		return $post;
	}

	public function isPublished(): bool
	{
		return $this->status === 'publish';
	}

	public function isDraft(): bool
	{
		return $this->status === 'draft';
	}

	public function markAsDraft()
	{
		if ($this->isDraft()) {
			throw new DomainException('Can not draft a drafted post');
		}
		$this->status = 'draft';
		$this->setUpdatedAt();
	}

	public function markAsPublish()
	{
		if ($this->isPublished()) {
			throw new DomainException('Can not publish a published post');
		}
		$this->status = 'publish';
		$this->setUpdatedAt();
	}

	public function changeTitle(string $title, Author $author)
	{
		if (false === $this->isWrittentBy($author)) {
			throw new DomainException('Only blog post author can change the post title');
		}
		$this->setTitle($title);
		$this->setUpdatedAt();
	}

	public function editBody(string $body, Author $author)
	{
		if (false === $this->isWrittentBy($author)) {
			throw new DomainException('Only blog post author can edit the post body');
		}
		$this->setBody($body);
		$this->setUpdatedAt();
	}
	
	public function isWrittentBy(Author $author): bool
	{
		return $this->author->equals($author);
	}

	public function addTag(string $tag)
	{
		if (count($this->tags) === 4) {
			throw new DomainException('Blog post can not have more than 4 tags');
		}

		if (false === $this->hasTag($tag)) {
			$this->tags[] = $tag;
			$this->setUpdatedAt();
		}
	}

	public function hasTag(string $tag): bool
	{
		return in_array($tag, $this->tags);
	}

	public function removeTag(string $tag)
	{
		if ($this->hasTag($tag)) {
			unset($this->tags[$tag]);
			$this->setUpdatedAt();
		}
	}

	private function setId($id)
	{
		$this->id = $id;
	}

	public function getId()
	{
		return $this->id;
	}

	private function setTitle($title)
	{
		if (empty($title)) {
			throw new InvalidArgumentException('Title must not be empty string');
		}

		if (strlen($title) > 250) {
			throw new InvalidArgumentException('Title must not be more than 250 characters');
		}

		$this->title = $title;
	}

	public function getTitle()
	{
		return $this->title;
	}

	private function setBody($body)
	{
		if (empty($body)) {
			throw new InvalidArgumentException('Body must not be empty string');
		}
		$this->body = $body;
	}

	public function getBody()
	{
		return $this->body;
	}

	private function setTags(array $tags)
	{
		$this->tags = $tags;
	}

	public function getTags()
	{
		return $this->tags;
	}

	public function getCreatedAt()
	{
		return $this->createdAt;
	}

	private function setCreatedAt()
	{
		$this->createdAt = new DateTimeImmutable("now", new DateTimeZone('Africa/Algiers'));
	}

	public function getUpdatedAt()
	{
		return $this->updatedAt;
	}

	private function setUpdatedAt()
	{
		$this->updatedAt = new DateTimeImmutable("now", new DateTimeZone('Africa/Algiers'));
	}
}