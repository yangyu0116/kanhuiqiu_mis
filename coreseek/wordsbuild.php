<?php
ini_set('memory_limit', '2G');
mysql_connect('localhost','video','video');
mysql_select_db('kanhuiqiu');
mysql_query('set names utf8');


$count = mysql_fetch_assoc(mysql_query('SELECT count(*) as count FROM tbl_words'));
$words_count = file_get_contents('words_count.txt');
if ($words_count == $count['count']){
	exit;
}


$sql = "SELECT word,samewords FROM tbl_words ORDER BY id DESC ";

$query = mysql_query($sql);
$segmentation_data = $sameword_data = '';
$separator = "\t1\nx:1\n";
while ($rt = mysql_fetch_assoc($query)){

	$tmp_seq = $rt['word'].$separator;

	if (!empty($rt['samewords'])){
		if (strstr($rt['samewords'], ',')){
			foreach (explode(',',$rt['samewords']) as $sw)
				$tmp_seq .= $sw.$separator;
		}else{
			$tmp_seq .= $rt['samewords'].$separator;
		}
	}

	$tmp_sameword = build_sameword($rt['word'].','.$rt['samewords']);


	$segmentation_data .= $tmp_seq;
	$sameword_data .= $tmp_sameword;
}

write_over('nba.txt', $segmentation_data);
write_over('thesaurus.txt', $sameword_data);


//复写文件
function write_over($filename,$data,$method="rb+",$iflock=1,$check=1,$chmod=1){
	$check && strpos($filename,'..')!==false && exit('Forbidden');
	touch($filename);
	$handle = fopen($filename,$method);
	$iflock && flock($handle,LOCK_EX);
	fwrite($handle,$data);
	$method=="rb+" && ftruncate($handle,strlen($data));
	fclose($handle);
	$chmod && @chmod($filename,0777);
}
function build_sameword($merge_word){
	$data = '';
	$merge_word_arr = explode(',',$merge_word);
	$count = count($merge_word_arr);
	foreach ($merge_word_arr as $main_k => $main_mw){
		$data .= $main_mw."\n-";
		foreach($merge_word_arr as $other_k => $other_mw)
			$data .= ($main_k!=$other_k) ? $other_mw.',' : '';
		$data .= "\n";
	}
	return $data;
}
