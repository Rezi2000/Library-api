<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Book;
use App\Models\BookAuthor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookController extends Controller
{

    private function create_book(string $title,int $published,bool $reserved){
        return Book::create([
            'book_name' => $title,
            'published' => $published,
            'reserved' => $reserved //1 - reserved, 0 - not reserved
        ]);
    }

    private function create_author(string $name,string $surname){
        return Author::create([
            'name' => $name,
            'surname' => $surname
        ]);
    }


    public function index_books()
    {
        return Book::all();
    }

    //specified book's author/authors
    public function show_authors($id)
    {
        return Book::findOrFail($id)->authors;
    }


    public function index_authors(){
        return Author::all();
    }

    //specified author's book/books
    public function show_books($id)
    {
        return Author::findOrFail($id)->books;
    }


    //store new book and new author
    public function store(Request $request)
    {

        $request->validate([
            'book_name' => 'required|string|unique:books',
            'published' => 'required|numeric|min:1900|max:'.(date('Y')),
            'reserved' => 'boolean',
            'author_name' => 'required|string',
            'author_surname' => 'required|string'
        ]);


        $book = $this->create_book($request->get('book_name'),$request->get('published'),
                                   $request->get('reserved'));


        $author = $this->create_author($request->get('author_name'),$request->get('author_surname'));


        $book_author = new BookAuthor();
        $book_author->author_id = $author->id;
        $book_author->book_id = $book->id;
        $book_author->save();

        return response([
            'message' => 'book has created'
        ]);

    }


    //add new author to existing book
    public function add_author($id,Request $request){


        $request->validate([
            'author_name' => 'required|string',
            'author_surname' => 'required|string'
        ]);


        $book = Book::findOrFail($id);

        $author = $this->create_author($request->get('author_name'),$request->get('author_surname'));

        BookAuthor::create([
           'author_id' =>  $author->id,
            'book_id' => $book->id
        ]);

        return response([
           'message' => 'Author has been added'
        ]);
    }


    //add new book to existing author
    public function add_book($id,Request $request){

        $request->validate([
            'book_name' => 'required|string|unique:books',
            'published' => 'required|numeric|min:1900|max:'.(date('Y')),
            'reserved' => 'boolean',
        ]);

        $author = Author::findOrFail($id);

        $book = $this->create_book($request->get('book_name'),$request->get('published'),
                                   $request->get('reserved'));

        BookAuthor::create([
            'author_id' =>  $author->id,
            'book_id' => $book->id
        ]);

        return response([
           'message' => 'Book has been added'
        ]);
    }


    //update book
    public function update(Request $request, $id)
    {
        $request->validate([
            'book_name' => 'string',
            'published' => 'numeric|min:1900|max:'.(date('Y')),
            'reserved' => 'boolean',
        ]);
        $book = Book::findOrFail($id);
        $book->update($request->all());
        return $book;
    }


    public function destroy_book($id)
    {
        Book::findOrFail($id)->delete();
        return response([
           'message' => 'book has deleted'
        ]);

    }

    public function destroy_author($id)
    {
        Author::findOrFail($id)->delete();
        return response([
            'message' => 'author has deleted'
        ]);

    }


    public function search($key){

        $pivot_table = DB::table('author_book')
            ->join('books', 'author_book.book_id', '=', 'books.id')
            ->join('authors', 'author_book.author_id', '=', 'authors.id')
            ->select('author_book.*')
            ->where([
                ['book_name','like', '%'.$key.'%']
            ])
            ->orWhere([
                ['name','like','%'.$key.'%']
            ])->get();


        $founded_books = [];

        foreach ($pivot_table as $item){
            $book = Book::findOrFail($item->book_id);
             array_push($founded_books,$book);
        }

        return $founded_books;

    }
}
