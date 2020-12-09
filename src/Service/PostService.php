<?php

namespace Blog\Service;

use Blog\Model\Post;
use Blog\Model\Posts;

class PostService
{
	private Posts $posts;
	private AuthorService $authorService;

	public function __construct(Posts $posts, AuthorService $authorService)
	{
		$this->posts = $posts;
		$this->authorService = $authorService;
	}
	
	public function draft(string $title, string $body, array $tags = [], string $author)
	{
		$author = $this->authorService->fromUsername($author);
		$post = Post::draft($title, $body, $tags, $author);
		$this->posts->save($post);
		return true;
	}
}