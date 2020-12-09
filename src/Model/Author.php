<?php

namespace Blog\Model;

class Author
{
	private string $firstname;
	private string $lastname;
	private ?string $biography;

	private function __construct(string $firstname, string $lastname, ?string $biography = null)
	{
		$this->firstname = $firstname;
		$this->lastname = $lastname;
		$this->biography = $biography;
	}

	public static function named(string $firstname, string $lastname): Author
	{
		return new Author($firstname, $lastname );
	}

	public function editBiography(string $biography)
	{
		$this->biography = $biography;
	}

	public function hasBiography()
	{
		return $this->biography != null;
	}

	public function equals(Author $other)
	{
		return (
				$this->firstname == $other->firstname 
				&& $this->lastname == $other->lastname 
				&& $this->biography == $other->biography
			);
	}

	/**
	 * Get the value of firstname
	 */ 
	public function getFirstname()
	{
		return $this->firstname;
	}

	/**
	 * Get the value of lastname
	 */ 
	public function getLastname()
	{
		return $this->lastname;
	}
}