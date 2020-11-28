<?php

namespace Blog\Model;

use InvalidArgumentException;

class Post
{
	private $id;
	private string $title;
	private string $body;
	private array $tags;
	private string $status;

	private function __construct(string $title, string $body, array $tags = [], string $author, string $status)
	{
		$this->setTitle($title);
		$this->setBody($body);
		$this->tags = $tags;
		$this->author = $author;
		$this->status = $status;
	}

	public static function publish(string $title, string $body, array $tags = [], string $author): Post
	{
		$post = new Post($title, $body, $tags, $author, 'publish');
		return $post;
	}

	public static function draft(string $title, string $body, array $tags = [], string $author): Post
	{
		$post = new Post($title, $body, $tags, $author, 'draft');
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
}