#!/bin/bash
echo enter file name
read fname
exec<$fname
while read line
do

echo $line

php bing-indexer.php $line

done
