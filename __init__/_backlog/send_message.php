<?php
// send email to rohhthone@gmail.com with content
$to = "rohhthone@gmail.com";
$from = "info@rohh.ru";
$subject = "New Order";
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
$headers = "From:" . $from;

if(mail($to,$subject,$content, $headers)) {
  echo "Message sent successfully.";
} else {
  echo "Message was not sent.";
}