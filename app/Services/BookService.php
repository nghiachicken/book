<?php


namespace App\Services;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use App\Book;

class BookService
{
    public function __construct()
    {
        
    }
    
    public function create_book($request)
    {
        $data['name'] = $request['name'];
        if(isset($request['image']))
        {
            $image = $this->uploadFile($request['image'],'Book');
            $data['image'] = $image;
        }
        
        $book = Book::create($data);
        if($book)
        {
            if($request['categories'])
            {
                foreach ($request['categories'] as $key => $value) {
                    $book->categories()->attach($value);
                }
            }
        }
        return $book;
    }

    function uploadFile($file,$folderName)
    {
        $s3 = \Storage::disk('local');
        $time = time();
        $fileName = preg_replace('/\s+/', '', $file->getClientOriginalName());
        $fileNameArr = explode('.', $fileName);
        $fileNameCustom = $fileNameArr[0] .'_'. $time .'.'. $fileNameArr[1];
        $filePath = 'public/assets/'.$folderName.'/' . $fileNameCustom;
        $url = '/assets/'.$folderName.'/'. $fileNameCustom;
        $result = $s3->put($filePath, file_get_contents($file),'public');
        if($result)
        {
            return $url;
        }
        return null; 
    }

    public function get_categories($id)
    {
        return DB::table("book_category")->where("book_category.book_id",$id)
            ->pluck('book_category.category_id')
            ->all();
    }

    public function edit_book($request,$id)
    {
        $book = Book::findOrFail($id);

        $data['name'] = $request['name'];
        if(isset($request['image']))
        {
            $image = $this->uploadFile($request['image'],'Book');
            $data['image'] = $image;
        }
        $book->update($data);
        if($book)
        {
            $category_book = $this->get_categories($id);
            if($category_book)
            {
                foreach ($category_book as $key => $value) {
                    $book->categories()->detach($value);
                }
            }
            if($request['categories'])
            {
                foreach ($request['categories'] as $key => $value) {
                    $book->categories()->attach($value);
                }
            }
        }
        return $book;
    }

    public function get_book($request)
    {
        $items = Book::select('books.id','books.name','books.image','book_category.category_id')
            ->join('book_category','book_category.book_id','=','books.id','left')
            ->join('categories','categories.id','=','book_category.category_id','left')->groupBy('books.id');
            if($request->select_category!= 0)
            {
                $items = $items->where('book_category.category_id','=',$request->select_category);
            }
            if($request->name_book!=null)
            {
                $items = $items->where('books.name','like','%' .$request->name_book. '%');
            }
        $items = $items->get();
        return $items;
    }
}