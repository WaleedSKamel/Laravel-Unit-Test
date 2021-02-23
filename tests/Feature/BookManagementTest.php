<?php

namespace Tests\Feature;

use App\Models\Author;
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

        $response = $this->post('/books',$this->data());

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
        $response = $this->post('/books',array_merge($this->data(),['author_id' => '']));

        $response->assertSessionHasErrors('author_id');
    }

    /** @test*/
    public function a_book_can_be_updated()
    {
        //$this->withoutExceptionHandling();

        $this->post('/books',$this->data());

        $books = Book::first();

        $response = $this->patch($books->path(),[
            'title' => 'New Title',
            'author_id' => 'New Author'
        ]);


        $this->assertEquals('New Title',Book::first()->title);
        $this->assertEquals(6,Book::first()->author_id);
        $response->assertRedirect($books->fresh()->path());
    }

    /** @test*/
    public function a_book_can_deleted()
    {
        //$this->withoutExceptionHandling();
        $this->post('/books',$this->data());


        $books = Book::first();
        $this->assertCount(1,Book::all());

        $response = $this->delete($books->path());

        $this->assertCount(0,Book::all());
        $response->assertRedirect('/books');
    }

    /** @test*/
    public function a_new_author_is_automatically_added()
    {
        //$this->withoutExceptionHandling();
        $this->post('/books',[
            'title' => 'Col Title',
            'author_id' => 'Waleed'
        ]);

        $books = Book::first();
        $author = Author::first();


        $this->assertEquals($author->id,$books->author_id);
        $this->assertCount(1,Author::all());
    }

    private  function data()
    {
        return [
            'title' => 'Col Title',
            'author_id' => 'Waleed'
        ];
    }
}
