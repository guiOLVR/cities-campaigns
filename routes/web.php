<?php

use Illuminate\Support\Facades\DB;

Route::get('/', function () {
  $visited = DB::select('select * from cities ');
  // $conn = new PDO("mysql:host=$servername;port=8889;dbname=AppDatabase", $username, $password); 


  return view('travel_list', ['visited' => $visited] );
});