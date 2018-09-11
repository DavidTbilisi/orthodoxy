<?php header( "Cache-Control: no-cache, must-revalidate" ); ?>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<button>upload</button>

<?php
require 'vendor/autoload.php';
// functions
function pp( $data ) {
	echo '<pre>';
	print_r( $data );
	echo '</pre>';
}
define( "BR", "<br />" );
function cl( $data ) {
	echo '<script>';
	echo 'console.log(' . json_encode( $data, JSON_FORCE_OBJECT ) . ')';
	echo '</script>';
}
$pdo = new PDO("mysql:dbname=bible;charset=utf8", "root");
$db = new FluentPDO($pdo);

$q = $db->from('ortodox')->orderBy('id desc')->limit(1);
echo 'ბოლო ატვირთული: ';


/************/
// change after uploading one book
$ch  = 1;
$wigni = '1timote';
$auto = true;
/************/
?>
<?php

 foreach ($q as $a):
    print_r($wigni.' '.$a['chapter_id'].':'.$a['verse_id']
            . ' <p style="color:darkslategray"> ' .$a['text'] ."</p>"
    );

// ****** auto next Chapters ******
 if ($auto) :
 $ch = ++$a['chapter_id']; $book = $a['book_id'];
 else:
// ************
	 // first chapter (inc book)
     $book = ++$a['book_id'];
 endif;
 endforeach;

$client = new GuzzleHttp\Client();

$url = 'http://www.orthodoxy.ge/tserili/biblia_sruli/akhali/'.$wigni.'/'.$wigni.'-' . $ch . '.htm';
//$url = 'http://www.orthodoxy.ge/tserili/biblia_sruli/akhali/'.$wigni.'/'.$wigni.'.htm';
echo BR,BR,BR,BR,"<a href='$url' target='_blank'>ბიბლია</a>";
try{
$res = $client->request( 'GET', $url);
echo $res->getBody();
}
catch (Exception $e){
	echo "<a href='http://www.orthodoxy.ge/tserili/biblia_sruli/sarchevi.php'> სარჩევი </a>";
	echo BR.'<h2>ასეთი გვერდი არ არსებობს</h2> ' . $e ;
    die;
}
?>


<script>
    console.clear();
    function myTrim(x) {
        return x.replace(/^\s+|\s+|\n+$/gm,' ');
    }



    console.log('\n\n\n');
    var json =[];
    var table = document.querySelectorAll('table')[2];
    var allRows = table.querySelectorAll('tr');

    allRows.forEach(function (element, index) {
        var obj = {};
        element.querySelectorAll('td').forEach(function (td, count) {

            switch (count) {
                case 0:
                    obj.id = parseInt(td.innerHTML);
                    break;
                case 1:
                    obj.book = <?php echo $book ?>;
                    obj.chapter = <?php echo $ch ?>;
                    obj.text = myTrim(td.innerHTML);
                    json.push(obj);
                    break;

            }
        });
    });

function parseToPost (obj) {
    var keys = Object.keys(obj);
    var str= '';
    keys.forEach(function (name,value) {
        str += name+'='+obj[name] +"&";
    });
    str = str.substr(0,str.length -1);
    return str;
}
function post(array,obj) {
    axios({
        method: 'post',
        url:'http://localhost/orthodoxy/post.php',
        data: parseToPost(array[obj]),
        headers: {'Content-Type': 'application/x-www-form-urlencoded'}
    })
        .then(function (res) {
            if (obj >= array.length-1) {
//                return;
                 location.reload();
            }
            post(array,obj+1);
        })
        .catch(function(e){
            console.log(e)
        });
}
function upload(e) {
    post(json,0);
    document.querySelector('button').style.display = 'none';
}
</script>

<?php if ($auto) : ?>
<script>document.querySelector('button').addEventListener('click', upload() );</script>
<?php else: ?>
<script>document.querySelector('button').addEventListener('click', upload );</script>
<?php endif ?>
