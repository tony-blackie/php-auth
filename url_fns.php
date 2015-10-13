<?php
require_once('db_fns.php');

function get_user_urls($username) {
  // »звлекает из базы данных все сохраненные пользователем URL-адреса
  $conn = db_connect();
  $result = $conn->query( "select bm_URL
                           from bookmark
                           where username = '$username'");
  if (!$result)
    return false;

  // —оздать массив URL-адресов
  $url_array = array();
  for ($count = 1; $row = $result->fetch_row(); ++$count) {
    $url_array[$count] = $row[0];
  }
  return $url_array;
};
  
function add_bm($new_url) {
  // ƒобавл€ет новую закладку в базу данных

  echo "ѕопытка добавлени€ ".htmlspecialchars($new_url).'<br />';
  $valid_user = $_SESSION['valid_user'];
  
  $conn = db_connect();

  // ѕроверить, существует ли така€ закладка
  $result = $conn->query("select * from bookmark
                         where username='$valid_user' 
                         and bm_URL='$new_url'");
  if ($result && ($result->num_rows>0))
    throw new Exception('“ака€ закладка уже существует.');

  // ¬ставить новую закладку
  if (!$conn->query( "insert into bookmark values
                     ('$valid_user', '$new_url')"))
    throw new Exception('Ќе удаетс€ вставить закладку в базу данных.'); 

  return true;
} 

function delete_bm($user, $url) {
  // ”дал€ет один URL-адрес из базы данных 
  $conn = db_connect();

  // ”далить закладку
  if (!$conn->query( "delete from bookmark
                      where username='$user' and bm_url='$url'"))
    throw new Exception('«акладка не может быть удалена.');
  return true;
}

function recommend_urls($valid_user, $popularity = 1) {
  // ћы попытаемс€ обеспечить дл€ пользователей выдачу *полуинтеллектуальных*
  // рекомендаций. ≈сли пользователи имеют URL-адрес, совпадающий с закладками
  // других пользователей, их могут заинтересовать и прочие URL-адреса, 
  // которые имеют другие пользователи 
  $conn = db_connect();

  // Ќайти других пользователей, закладки которых
  // совпадают с закладкой текущего пользовател€.
  // ¬ качестве простейшего способа исключени€ из рассмотрени€
  // приватных страниц посетителей, а также дл€ более совершенной
  // рекомендации мы устанавливаем минимальный уровень попул€рности.
  // ≈сли $popularity = 1, могут рекомендоватьс€ лишь 
  // адреса, сохраненные более чем одним пользователем
  
  $query = "select bm_URL
            from bookmark
            where username in (
              select distinct(b2.username)
              from bookmark b1, bookmark b2
              where b1.username = '".$valid_user."'
                and b1.username != b2.username )
            and bm_URL not in (
              select bm_URL
              from bookmark
              where username='".$valid_user."' )
            group by bm_URL
            having count(bm_URL) > ".$popularity;

  if (!($result = $conn->query($query)))
    throw new Exception('Ќе удаетс€ найти закладки дл€ рекомендации.');
  if ($result->num_rows==0)
    throw new Exception('Ќе удаетс€ найти закладки дл€ рекомендации.');

  $urls = array();

  // —формировать массив подход€щих URL-адресов
  for ($count=0; $row = $result->fetch_object(); $count++) {
    $urls[$count] = $row->bm_URL; 
  }
                              
  return $urls; 
}

?>
