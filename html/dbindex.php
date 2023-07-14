<?php

function connect_db(){
  //ホスト名、データベース名、文字コードの３つを定義する
  $host = 'mysql5.7';
  $db = 'test';
  $charset = 'utf8';
  $dsn = "mysql:host=$host; dbname=$db; charset=$charset";

  //ユーザー名、パスワード
  $user = 'test';
  $pass = 'test';

  //オプション
  $options = [
      PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
      PDO::ATTR_EMULATE_PREPARES   => false,
  ];

  try{

      //上のデータを引数に入れて、PDOインスタンスを作成
      $pdo = new PDO($dsn, $user, $pass, $options);

  }catch(PDOException $e){
      echo $e->getMessage();
  }

  //PDOインスタンスを返す
  return $pdo;
}

$pdo = connect_db();
?>