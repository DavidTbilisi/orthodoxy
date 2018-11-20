<?php  require_once 'rb.php';
R::setup('mysql:host=127.0.0.1;dbname=bible',
	'root', '');

R::ext('update', function( $type, $key, $value, $where ){
	return R::exec("UPDATE `{$type}` SET `{$key}`= '{$value}' {$where}");
});
R::debug(true);
R::ext('updateAll', function( $type, $array, $where){
	$sql = "UPDATE `{$type}` SET ";
	foreach ($array as $kay=>$value):
		$sql .= "`{$kay}` = '{$value}'";
		endforeach;
	$sql .= " {$where}";
	echo $sql;
	 return R::exec($sql);
});
//$count_bible = R::exec("SELECT book, name, COUNT(DISTINCT chapter) as chapter_count, COUNT(verse) AS verse_count FROM `bible1` LEFT JOIN books on books.id = bible1.book GROUP BY book");



?>

<div id="root">
<input type="text" @change="showResult" v-model="word" > <span>ნაპოვნია {{length}}</span>
<p></p>
<button>ძიება</button>
<p></p>

<div v-if="result">
	<ul>
		<li v-for="(row, index) in result">
			<b>{{++index}}. {{bookTitles[parseInt(row.book)]}} {{row.chapter}}:{{row.verse_num}} </b> <span v-html="highlight(row.verse, word)"></span>
			<p></p>
		</li>

	</ul>
</div>
	<div v-else-if="result.length === 0">
		მოძებნეთ სიტყვა
	</div>
</div>



<script src="./node_modules/vue/dist/vue.js"></script>
<script src="js/bundle.js"></script>