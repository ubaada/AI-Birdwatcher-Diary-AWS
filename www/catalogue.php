<?php

# get supported bird species from AIserver.
echo file_get_contents("http://192.168.2.13:5000/list");

?>