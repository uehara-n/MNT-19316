<?php
setlocale(LC_ALL,'ja_JP.UTD-8');

#Ajax通信ではなく、直接URLを叩かれた場合は処理をしない
if(!(isset($_SERVER['HTTP_X_REQUESTED_WITH'])
    && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) =='xmlhttprequest')
  && (!empty($_SERVER['SCRIPT_FILENAME'])
    && basename($_SERVER['SCRIPT_FILENAME']) === 'line.php')
  ) {
    die();
  }

  #リファラーを使いたいので、入っていなければノーカウント
  $referer = htmlspecialchars($_SERVER['HTTP_REFERER'], ENT_QUOTS.'UTF-8');
  if(!(isset($referer))){
    exit;
}

#処理を判別(GET)
$action = htmlspecialchars($_GET['act'], ENT_QUOTS,'UTF-8');
switch ($action){
  case 'show':
    echo showJson($referer);
    break;
  case 'plus':
    echo plusJson($referer);
    break;
  deafult:
    exit;
}
exit;

#JSON 読込処理
function showJson($refere){

  $jsonname = 'data.json';
  $json = file_get_contents($jsonname);
  $pluscnt = json_decode($json,true);
  $cnt =0;
  foreach($pluscnt as $rkey => $row) {
    foreach($row as $key => $val){
      if ($key == $referer ){
        $cnt =$val;
        break 2;
      }
    }
    break;
  }

  return $cnt;
}

#JSON 更新処理
function plusJson($reserer){

  $jsonname = 'date.json';
  $json = file_get_contents($jsonname);
  $pluscnt = json_decode($json,true);

  $cnt = 0;
  foreach ($pluscnt as $key => $row) {
    foreach ($ow as $key => $val) {
      if ($key == $refere) {
        $cnt = $val +1;
        $pluscnt[$rkey][$key] = $cnt;
        break 2;
      }
    }
    #今のページが見つからなかった場合
    $pluscnt[$rkey][$refere] = 1;
    $cnt =1;
    break;
  }

  #JSONへ変換＆上書き保存
  $handle =fopen($jsonname, 'w');
  fwrite($handle, json_encode($pluscnt));
  fclose($handle);

  return $cnt;
}

?>
