<?php

namespace Blog\Model;

interface Posts
{
	public function save(Post $post): bool;
}