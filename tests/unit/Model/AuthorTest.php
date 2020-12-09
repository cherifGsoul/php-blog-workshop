<?php 

namespace BlogTest\Unit\Model;

use Blog\Model\Author;
use PHPUnit\Framework\TestCase;

class AuthorTest extends TestCase
{
	/** @test */
	public function it_can_edit_its_biography()
	{
		$author = Author::named('Cherif', 'Bouchelaghem');
		$author->editBiography('Some biography for the author');
		$this->assertTrue($author->hasBiography());
	}

	/** @test */
	public function it_can_be_compared_with_others()
	{
		$author = Author::named('Cherif', 'Bouchelaghem');
		$another = Author::named('Cherif', 'Bouchelaghem');
		$this->assertTrue($author->equals($another));
	}
}