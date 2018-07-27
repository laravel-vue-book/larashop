<?php

namespace App;


use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public function user(){
        return $this->belongsTo('App\User');
    }

    public function books(){
        return $this->belongsToMany('App\Book')->withPivot('quantity');
    }

    public function getTotalQuantityAttribute(){
        $total_quantity = 0;
        
        foreach($this->books as $book){
            $total_quantity += $book->pivot->quantity;
        }

        return $total_quantity;
    }
}
