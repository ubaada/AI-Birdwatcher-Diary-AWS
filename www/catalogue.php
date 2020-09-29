<?php

# get supported bird species from AIserver.
echo file_get_contents("http://172.31.16.13:5000/list");

?>