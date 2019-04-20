<?php


namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Book;
use App\Category;
use DB;
use App\Services\BookService;
use Datatables;
use App\Http\Requests\CreateBookRequest;
use App\Http\Requests\EditBookRequest;


class BookController extends Controller
{
    protected $service;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct(BookService $service)
    {
         $this->middleware('permission:book-list');
         $this->middleware('permission:book-create', ['only' => ['create','store']]);
         $this->middleware('permission:book-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:book-delete', ['only' => ['delete']]);
         $this->service = $service;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $categories = Category::all();
        $books = Book::orderBy('id','DESC')->get();
        return view('books.index',compact('categories','books'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function data_book(Request $request)
    {
        $books = $this->service->get_book($request);
        $collection = collect($books);
        return Datatables::of($collection)
            ->addColumn('categories',function($item)
            {
                return get_category($item);
            })
            ->editColumn('image',function($item){
                if($item->image)
                {
                    return '<img src="'.$item->image.'" alt="" style="height: 150px;width: auto;">';
                }
            })
            ->addColumn('button',function($item)
            {
                return '<a class="btn btn-primary" href="'.route('books.edit',$item->id).'">Edit</a><a class="btn btn-danger"  href="'.route('books.delete',$item->id).'">Delete</a>';
            })
            ->rawColumns(['button','image'])
            ->make(true);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $categories = Category::pluck('name','id')->all();
        return view('books.create',compact('categories'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateBookRequest $request)
    {
        $this->service->create_book($request->all());
        
        return redirect()->route('books.index')
                        ->with('success','Books created successfully');
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $book = Book::findOrFail($id); 
        $categories = Category::pluck('name','id')->all();
        $category_book = $this->service->get_categories($id);

        return view('books.edit',compact('categories','category_book','book'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EditBookRequest $request, $id)
    {
        $this->service->edit_book($request->all(),$id);
        return redirect()->route('books.index')
                        ->with('success','Book updated successfully');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        DB::table("books")->where('id',$id)->delete();
        return redirect()->route('books.index')
                        ->with('success','Book deleted successfully');
    }
}