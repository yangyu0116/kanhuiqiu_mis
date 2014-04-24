#!/bin/bash

cd /home/video/coreseek

/usr/local/mmseg3/bin/mmseg -u /home/video/coreseek/nba.txt

mv /home/video/coreseek/nba.txt.uni /home/video/coreseek/uni.lib
mv /home/video/coreseek/uni.lib /usr/local/mmseg3/etc/

/usr/local/mmseg3/bin/mmseg -t /home/video/coreseek/thesaurus.txt
mv /home/video/coreseek/thesaurus.lib /usr/local/mmseg3/etc

/usr/local/coreseek/bin/indexer -c /home/video/coreseek/csft.conf --all --rotate
/usr/local/coreseek/bin/searchd  -c /home/video/coreseek/csft.conf --stop
/usr/local/coreseek/bin/searchd  -c /home/video/coreseek/csft.conf

