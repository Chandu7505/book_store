<?php
function testGetBooks() {
  $response = file_get_contents('http://localhost/api/index.php');
  assert(!empty($response), "Books should be returned");
}
testGetBooks();
?>
