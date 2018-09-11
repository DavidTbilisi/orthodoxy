<?php header("Cache-Control: no-cache, must-revalidate");?>

	<html>
	<head>
		<meta charset="UTF-8">
		<meta name="viewport"
		      content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
		<meta http-equiv="X-UA-Compatible" content="ie=edge">
		<title>Document</title>
		<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
	</head>
	<body>
	<script>
        //http://holybible.ge/breadphp.php
        axios.get('http://www.orthodoxy.ge/tserili/biblia_sruli/dzveli/dabadeba/dabadeba-1.htm')
	        .then(function (response) {
            console.log(response);
        }).catch(function (error) {
            console.log(error)
        });


	</script>
	</body>
	</html>

<?php

require 'vendor/autoload.php';


$pdo = new PDO("mysql:dbname=bible;charset=utf8", "root");
$db = new FluentPDO($pdo);


if(isset($_POST['book'])) {
    $p = $_POST;
    $values =
        [
            'id' => null,
            'verse_id' => $p['id'],
            'chapter_id' => $p['chapter'],
            'book_id' => $p['book'],
            'text' => $p['text']
        ];
 $db->insertInto('ortodox',$values)->execute();

}

?>

<script>







    /*
    *
    *
    * var verseNumb = document.querySelectorAll('.verse-num'),
     verseT = document.querySelectorAll('.verse')
     var json = [];
     verseT.forEach(function (el,id) {
     var obj = {}
     obj.id = ++id;
     obj.text = el.innerHTML;
     obj.book = 15;
     obj.chapter = 2;
     json.push(obj)
     })
     console.log(JSON.stringify(json) )
    *
    *
    * */


</script>
