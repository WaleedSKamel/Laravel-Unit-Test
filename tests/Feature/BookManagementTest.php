<?php

namespace Tests\Feature;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BookManagementTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function a_book_can_be_to_the_library()
    {
        // if you want real error
        //$this->withoutExceptionHandling();

        $response = $this->post('/books',[
            'title' => 'Book',
            'author' => 'Waleed'
        ]);

        $books = Book::first();

        //$response->assertOk();
        $this->assertCount(1,Book::all());
        $response->assertRedirect($books->path());
    }

    /** @test*/
    public function a_title_is_required()
    {
        $response = $this->post('/books',[
            'title' => '',
            'author' => 'Waleed'
        ]);

        $response->assertSessionHasErrors('title');
    }

    /** @test*/
    public function a_author_is_required()
    {
        $response = $this->post('/books',[
            'title' => 'Col Title',
            'author' => ''
        ]);

        $response->assertSessionHasErrors('author');
    }

    /** @test*/
    public function a_book_can_be_updated()
    {
        //$this->withoutExceptionHandling();
        $this->post('/books',[
            'title' => 'Col Title',
            'author' => 'Waleed'
        ]);

        $books = Book::first();

        $response = $this->patch($books->path(),[
            'title' => 'New Title',
            'author' => 'New Author'
        ]);

        $this->assertEquals('New Title',Book::first()->title);
        $this->assertEquals('New Author',Book::first()->author);

        $response->assertRedirect($books->fresh()->path());
    }

    /** @test*/
    public function a_book_can_deleted()
    {
        $this->withoutExceptionHandling();
        $this->post('/books',[
            'title' => 'Col Title',
            'author' => 'Waleed'
        ]);


        $books = Book::first();
        $this->assertCount(1,Book::all());

        $response = $this->delete($books->path());

        $this->assertCount(0,Book::all());
        $response->assertRedirect('/books');
    }
}
