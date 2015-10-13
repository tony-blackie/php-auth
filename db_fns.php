<?php

function db_connect() {
  $result = new mysqli('localhost', 'root', '123', 'bookmarks');
  if (!$result)
    throw new Exception("Can't connect to the database.");
  else
    return $result;
}
