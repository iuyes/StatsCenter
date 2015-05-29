#! /bin/sh

# 安装脚本
# @ shiguangqi
#
ROOT=/data/webroot/stats.duowan.com/server/stats_web
# 安装server核心部分
NAME=server
DATE=$(date +%Y%m%d%H:%M:%S)

mv $ROOT/$NAME $ROOT/backup/$NAME.${DATE}.bak

if [ $? -eq 0 ];then
    mv $NAME $ROOT/$NAME
elif [ $? -eq 0 ];then
    exit 0
else
    exit 1
fi