#!/bin/bash

ROOTDIR=$(cd "$(dirname "$0")/.."; pwd)
cd $ROOTDIR || exit

# 1. 域名相关替换

echo "input project domain: "
while read DOMAIN
do
if [ ""x != "$DOMAIN"x ]; then 
	break 
fi
done

sed -i "s/{DOMAIN}/${DOMAIN}/g"  webroot/index.php
sed -i "s/{DOMAIN}/${DOMAIN}/g"  tool/*.php
mv inf/api.domain.com inf/${DOMAIN}
mv inf/test-api.domain.com inf/test-${DOMAIN}
cd inf && ln -s test-${DOMAIN} local-${DOMAIN} 
cd ../


# 2. configSys.php 内变量替换

echo "input appid: "
while read APPID
do
if [ ""x != "$APPID"x ]; then 
	break 
fi
done
sed -i "s/{APPID}/${APPID}/g" `find inf/ -type f -name "configSys.php"`

echo "input appname: "
while read APPNAME
do
if [ ""x != "$APPNAME"x ]; then 
	break 
fi
done
sed -i "s/{APPNAME}/${APPNAME}/g" `find inf/ -type f -name "configSys.php"`

# 3. configDb.php 内变量替换
echo "input db host: [default: 127.0.0.1] "
read DBHOST
if [ ""x == "$DBHOST"x ];then
	DBHOST="127.0.0.1"
fi
sed -i "s/{DBHOST}/${DBHOST}/g" `find inf/ -type f -name "configDb.php"`

echo "input db name: "
while read DBNAME
do
if [ ""x != "$DBNAME"x ]; then 
	break 
fi
done
sed -i "s/{DBNAME}/${DBNAME}/g" `find inf/ -type f -name "configDb.php"`

# 4. configCache.php 内变量替换
echo "input redis host: [default: 127.0.0.1] "
read REDIS_HOST
if [ ""x == "$REDIS_HOST"x ];then
	REDIS_HOST="127.0.0.1"
fi
sed -i "s/{REDIS_HOST}/${REDIS_HOST}/g" `find inf/ -type f -name "configCache.php"`

echo "input redis db: "
while read REDIS_DB
do
if [ ""x != "$REDIS_DB"x ]; then 
	break 
fi
done
sed -i "s/{REDIS_DB}/${REDIS_DB}/g" `find inf/ -type f -name "configCache.php"`


echo "===========DONE============"

# 6. 移除自身脚本
echo "remove init shell (y/n)? [default: y]"
read INPUT
if [ "N"X != `echo $INPUT"X"|tr '[a-z]' '[A-Z]'` ]; then
	/bin/rm ./tool/$0
fi


