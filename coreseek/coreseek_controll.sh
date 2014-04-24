#!/bin/bash

cd /home/video/coreseek

php wordsbuild.php

video_count=`/usr/bin/mysql -uvideo -pvideo kanhuiqiu -e'select count(*) as count from tbl_video'`
video_count=${video_count:5}

count_record=`cat count_record.txt`


if [ ${video_count} -ne ${count_record} ]; then
	echo ${video_count} > count_record.txt
	/home/video/coreseek/coreseek.sh > /dev/null 2>&1
fi
