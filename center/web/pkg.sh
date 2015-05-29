tar zcvf apps.tar.gz apps/
upload_client.php -h stats.duowan.com -p 9507 -f apps.tar.gz
rm apps.tar.gz

