<?php

function do_html_header($title) {

?>
  <html>
  <head>
    <title><?php echo $title;?></title>
    <style>
      body { font-family: Arial, Helvetica, sans-serif; font-size: 13px }
      li, td { font-family: Arial, Helvetica, sans-serif; font-size: 13px }
      hr { color: #3333cc; width=300; text-align=left }
      a { color: #000000 }
    </style>
  </head>
  <body>
  <img src="bookmark.gif" alt="PHPbookmark" border=0
       align="left" valign="bottom" height="55" width="57" />
  <h1>&nbsp;PHPbookmark</h1>
  <hr />
<?php
  if($title)
    do_html_heading($title);
}

function do_html_footer() {

?>
  </body>
  </html>
<?php
}

function do_html_heading($heading) {

?>
  <h2><?php echo $heading;?></h2>
<?php
}

function do_html_URL($url, $name) {

?>
  <br /><a href="<?php echo $url;?>"><?php echo $name;?></a><br />
<?php
}

function display_site_info() {

?>
  <ul>
    <li>Store your bookmarks online with us!
    <li>See what other users use!
    <li>Share your favourite links with others!
  </ul>
<?php
}

function display_login_form() {
?>
  <a href="register_form.php">Not a member?</a>
  <form method="post" action="member.php">
  <table bgcolor="#cccccc">
    <tr>
      <td colspan=2>Members login here:</td>
    </tr>
    <tr>
      <td>Username:</td>
      <td><input type="text" name="username"></td>
    </tr>
    <tr>
      <td>Password:</td>
      <td><input type="password" name="passwd"></td>
    </tr>
    <tr>
      <td colspan=2 align="center">
      <input type="submit" value="Log in"></td>
    </tr>
    <tr>
      <td colspan=2><a href="forgot_form.php">Forgot your password?</a></td>
    </tr>
  </table>
  </form>
<?php
}

function display_registration_form() {
?>
  <form method="post" action="register_new.php">
  <table bgcolor="#cccccc">
    <tr>
      <td>Email address:</td>
      <td><input type="text" name="email" size=30 maxlength=100></td>
    </tr>
    <tr>
      <td>Preferred username <br />(max 16 chars):</td>
      <td valign="top">
        <input type="text" name="username" size=16 maxlength=16>
      </td>
    </tr>
    <tr>
      <td>Password <br />(between 6 and 16 chars):</td>
      <td valign="top">
        <input type="password" name="passwd" size=16 maxlength=16>
      </td>
    </tr>
    <tr>
      <td>Confirm password:</td>
      <td>
        <input type="password" name="passwd2" size=16 maxlength=16>
      </td>
    </tr>
    <tr>
      <td colspan=2 align="center">
        <input type="submit" value="Register">
      </td>
    </tr>
  </table>
  </form>
<?php 
}

function display_user_urls($url_array) {
  global $bm_table;
  $bm_table = true;
?>
  <br />
  <form name="bm_table" action="delete_bms.php" method="post">
  <table width=300 cellpadding=2 cellspacing=0>
<?php
  $color = "#cccccc";
  echo "<tr bgcolor='$color'><td><strong></strong></td>";
  echo "<td><strong>�������?</strong></td></tr>";
  if (is_array($url_array) && count($url_array) > 0) {
    foreach ($url_array as $url) {
      if ($color == "#cccccc")
        $color = "#ffffff";
      else
        $color = "#cccccc";

      echo "<tr bgcolor='$color'><td><a ref=\"$url\">" . htmlspecialchars($url) . "</a></td>";
      echo "<td><input type='checkbox' name=\"del_me[]\"value=\"$url\"></td>";
      echo "</tr>"; 
    }
  } else
    echo "<tr><td>��� ����������� ��������</td></tr>";
?>
  </table> 
  </form>
<?php
}

function display_user_menu() {
  // ������� ���� ����� ��� ������ ��������
?>
  <hr />
  <a href="member.php">�������</a>&nbsp;|&nbsp;
  <a href="add_bm_form.php">�������� ��������</a>&nbsp;|&nbsp; 
<?php
  // ����� �������� ����� ������ �����, ����� �� �������� �������� ������� ��������
  global $bm_table;
  if($bm_table == true)
    echo "<a href='#' onClick='bm_table.submit();'>������� ��������</a>&nbsp;|&nbsp;"; 
  else
    echo "<font color='#cccccc'>������� ��������</font>&nbsp;|&nbsp;"; 
?>
  <a href="change_passwd_form.php">������� ������</a>
  <br />
  <a href="recommend.php">������������� ������ ���</a>&nbsp;|&nbsp;
  <a href="logout.php">�����</a> 
  <hr />
<?php
}

function display_add_bm_form() {
  // ���������� ����� ��� ����� ����� ��������
?>
  <form name="bm_table" action="add_bms.php" method="post">
  <table width=250 cellpadding=2 cellspacing=0 bgcolor="#cccccc">
    <tr>
      <td>�����<br />��������:</td>
      <td>
        <input type="text" name="new_url" value="http://" size=30 maxlength=255>
      </td>
    </tr>
    <tr>
      <td colspan=2 align="center">
        <input type="submit" value="�������� ��������">
      </td>
    </tr>
  </table>
  </form>
<?php
}

function display_password_form() {
  // ������� HTML-����� ��������� ������
?>
  <br />
  <form action="change_passwd.php" method="post">
  <table width=250 cellpadding=2 cellspacing=0 bgcolor="#cccccc">
    <tr>
      <td>������ ������:</td>
      <td><input type="password" name="old_passwd" size=16 maxlength=16></td>
    </tr>
    <tr>
      <td>����� ������:</td>
      <td><input type="password" name="new_passwd" size=16 maxlength=16></td>
    </tr>
    <tr>
      <td>������������� ������<br/>������:</td>
      <td><input type="password" name="new_passwd2" size=16 maxlength=16></td>
    </tr>
    <tr>
      <td colspan=2 align="center"><input type="submit" value="�������� ������"></td>
    </tr>
  </table>
  <br />
<?php
};

function display_forgot_form() {
  // ����� HTML-����� ��� ������������� � �������� ������ �� ����������� �����
?>
  <br />
  <form action="forgot_passwd.php" method="post">
  <table width=250 cellpadding=2 cellspacing=0 bgcolor='#cccccc'>
    <tr>
      <td>������� ���:</td>
      <td><input type="text" name="username" size=16 maxlength=16></td>
    </tr>
    <tr>
      <td colspan=2 align="center">
        <input type="submit" value="�������������� ������">
      </td>
    </tr>
  </table>
  <br />
<?php
};

function display_recommended_urls($url_array) {
  // ������� ���������� ������� ������� display_user_urls().
  // ������ ������ �������� ����������� ������������ ������� ������������� ��� ��������
?>
  <br />
  <table width=300 cellpadding=2 cellspacing=0>
<?php
  $color = "#cccccc";
  echo "<tr bgcolor=$color><td><strong>������������</strong></td></tr>";
  if (is_array($url_array) && count($url_array) > 0) {
    foreach ($url_array as $url) {
      if ($color == "#cccccc")
        $color = "#ffffff";
      else
        $color = "#cccccc";
      echo "<tr bgcolor='$color'><td><a ref=\"$url\">" .         htmlspecialchars($url) . "</a></td></tr>";
    }
  } else
    echo "<tr><td>�� ������� ������������ ���.</td></tr>";
?>
  </table>
<?php
};

?>
