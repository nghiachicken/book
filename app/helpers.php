<?php
if (! function_exists('get_category')) {
    function get_category($book) {
        $categories = $book->categories->pluck('name')->toArray();

        if(count($categories)>0)
        {
        	$string = implode(',',$categories);
        	return $string;
        }
        return null;
    }
}
