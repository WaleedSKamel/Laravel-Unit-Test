<?php

namespace Tests\Feature;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BookReservationTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function a_book_can_be_to_the_library()
    {
        // if you want real error
        $this->withoutExceptionHandling();

        $response = $this->post('/books',[
            'title' => 'Book',
            'author' => 'Waleed'
        ]);

        $response->assertOk();

       $this->assertCount(1,Book::all());
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

        $response = $this->patch('/books/'.$books->id,[
            'title' => 'New Title',
            'author' => 'New Author'
        ]);

        $this->assertEquals('New Title',Book::first()->title);
        $this->assertEquals('New Author',Book::first()->author);
    }
}
