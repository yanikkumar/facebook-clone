<?php

include '../load.php';
include '../../connect/login.php';

$userid = login::isLoggedIn();

if (isset($_POST['editedTextVal'])) {
    $editedTextVal = $_POST['editedTextVal'];
    $userid = $_POST['userid'];
    $postid = $_POST['postid'];

    $loadFromPost->postUpd($userid, $postid, $editedTextVal);

    // echo $editedTextVal;
}

if (isset($_POST['deletePost'])) {
    $deletePost = $_POST['deletePost'];
    $userid = $_POST['userid'];

    $loadFromUser->delete('post', array('post_id' => $postid, 'userId' => $userid));
}
