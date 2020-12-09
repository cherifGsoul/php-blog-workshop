<?php

namespace Blog\Service;

use Blog\Model\Author;

interface AuthorService
{
	public function fromUsername(string $username): Author;
}