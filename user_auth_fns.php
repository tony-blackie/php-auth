<?php

require_once('db_fns.php');

function register($username, $email, $password) {
  $conn = db_connect();


  $result = $conn->query("select * from user where username='$username'"); 
  if (!$result)
    throw new Exception('���������� ��������� ������ � ��');
  if ($result->num_rows > 0) 
    throw new Exception('��� ��� ������������ ��� ������ - ��������� '
                         .'�� ����� ����������� � �������� ������ ���.');

  $result = $conn->query("insert into user values 
                         ('$username', sha1('$password'), '$email')");
  if (!$result)
    throw new Exception('���������� ���������� � �� - ����������� �����.');

  return true;
}

function login($username, $password) {

  $conn = db_connect();

  $result = $conn->query("select * from user
            where username='$username' and passwd = sha1('$password')");
  if (!$result)
    throw new Exception('���� � ������� ����������');

  if ($result->num_rows > 0)
    return true;
  else
    throw new Exception('���� � ������� ����������');
}

function check_valid_user() {
  // ����������, ����� �� ������������ � ������� �, 
  // ���� ���, ������� ��������������� �����������

  global $valid_user;
  if (isset($_SESSION['valid_user'])) {
    echo '�� ����� � ������� ��� ������ '
      .stripslashes($_SESSION['valid_user']).'.';
    echo "<br />";
  } else {
    // ������������ �� ����� � �������
    do_html_heading("��������:");
    echo "�� �� ����� � �������.<br />";
    do_html_url('login.php', '����');
    do_html_footer();
    exit;
  }
}

function change_password($username, $old_password, $new_password) {
  // �������� ������ ������ �����.
  // ���������� �������� true ��� ���������� ����������

  // ���� ������� ������ ������ ���������,
  // �� ���������� ����� � ������������ �������� true,
  // � ��������� ������ ������������ ����������
  login($username, $old_password);
  $conn = db_connect();
  $result = $conn->query(
	  "update user
      set passwd = password('$new_password')
      where username = '$username'"
  );
  if (!$result)
    throw new Exception('������ �� ����� ���� �������.'); 
  else
    return true;  	// ������ ������� �������
}

function get_random_word($min_length, $max_length) {
// ��������� � ������� ���������� ����� ������
// � ���������� ����� ����� ��������� ����������

  // ��������� ���������� �����
  $word = '';
  // �������� ���� ��������, ����� �� �������������� ����� �������
  $dictionary = '/usr/dict/words';  // �������
  $fp = @fopen($dictionary, 'r');
  if(!$fp) return false; 
  $size = filesize($dictionary);

  // ������� � ��������� ������� �������
  srand ((double) microtime() * 1000000);
  $rand_location = rand(0, $size);
  fseek($fp, $rand_location);

  // ��������� �� ����� ����� ����� ������ �����
  while (strlen($word)< $min_length || strlen($word)>$max_length || strstr($word, "'")) {  
    if (feof($fp))
      fseek($fp, 0);         // ����� �� ����� - ��������� � �����
    $word = fgets($fp, 80);  // ������� ������� �����, �.�. ��� ����� ���� ���������
    $word = fgets($fp, 80);  // ��������� ������
  };
  $word=trim($word);         // �������� �������������� \n �� fgets
  return $word;  
}

function reset_password($username) {
  // ������������� ��������� �������� ��� ������.
  // ���������� ����� ������ ���� �������� false � ������ ������

  // �������� ��������� ����� �� ������� ������ �� 6 �� 13 ��������
  $new_password = get_random_word(6, 13);

  if($new_password == false)
    throw new Exception('���������� ������������� ����� ������.');
  // �������� � ���� ����� �� 0 �� 999
  // � ����� ���������� ��������� 
  srand ((double) microtime() * 1000000);
  $rand_number = rand(0, 999);
  $new_password .= $rand_number;

  // �������� ������ � ���� ������ ��� ������� �������� false
  $conn = db_connect();
  $result = $conn->query( "update user
                           set passwd = sha1('$new_password')
                           where username = '$username'");
  if (!$result)
    throw new Exception('���������� �������� ������.');  // ������ �� �������
  else
    return $new_password;  // ������ ������� �������
}

function notify_password($username, $password) {
  // ���������� ������������ � ���, ��� ��� ������ �������

  $conn = db_connect();
  $result = $conn->query(
      "select email from user
      where username='$username'");
  if (!$result) {
    throw new Exception('����� ����������� ����� �� ������.'); 
  } else if ($result->num_rows == 0) {
    throw new Exception('����� ����������� ����� �� ������.');
  } else {
    $row = $result->fetch_object();
    $email = $row->email;
    $from = "From: support@phpbookmark \r\n";
    $mesg = "��� ������ ��� ����� � ������� PHPBookmark ������� �� $password \r\n"
            ."����������, ������ ��� ��� ������� ����� � �������. \r\n";
          
    if (mail($email, '���������� � ����� � ������� PHPBookmark', $mesg, $from))
      return true;      
    else
      throw new Exception('�� ������� ��������� ����������� �����.'); 
  }
}

?>
