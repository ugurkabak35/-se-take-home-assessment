<?php
try {
  $db = new PDO("mysql:host=localhost;dbname=ideasoft;charset=UTF8", "root", "");
} catch (PDOException $hata) {
  echo "Bağlantı hatası <br>" . $hata->getMessage();
  die();
}
?>