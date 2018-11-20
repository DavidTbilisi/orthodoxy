<?php

require 'vendor/autoload.php';

require_once 'rb.php';




R::setup( "mysql:dbname=moswavleebi;charset=utf8", "root", '' );
R::debug( TRUE );

function dd($data){
    echo '<pre>';
    print_r($data);
    echo '</pre>';
}


$a = new __;


?>
<div id="root">






    <input @change="getBible" tabindex="1" v-model="book" placeholder="book" type="number">
    <input @change="getBible" v-model="chapter" placeholder="chapter" type="number">
    <input  v-model="verse" placeholder="verse" type="text">
    <button @click="getBible">get</button>
    <input  @click="toDb(1)" type="button" value="save">
    <button @click="auto=auto?false:true">
       auto {{auto}}
    </button>

<p> {{bookAplha}}: {{chapter}}</p>
<p> chapters in this book: {{maxChapt}}</p>

    <p v-for="( v, i ) in verses"> <span>{{++i}}</span>  {{v}} </p>


</div>



<script src="./node_modules/vue/dist/vue.js"></script>
<script src="js/bundle.js"></script>