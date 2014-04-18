# Define your item pipelines here
#
# Don't forget to add your pipeline to the ITEM_PIPELINES setting
# See: http://doc.scrapy.org/topics/item-pipeline.html
from scrapy import log
from twisted.enterprise import adbapi
from scrapy.http import Request
from scrapy.exceptions import DropItem
#import time
#import jieba
#jieba.load_userdict('user_dict.txt') 
import MySQLdb
import MySQLdb.cursors
 
#MySQLStorePipeline
class NbavideoPipeline(object):
 
    def __init__(self):
        self.db = adbapi.ConnectionPool('MySQLdb',
            db = 'kanhuiqiu',
            user = 'root',
            passwd = 'root',
            cursorclass = MySQLdb.cursors.DictCursor,
            charset = 'utf8',
            use_unicode = True
        )
 
    def process_item(self, item, spider):

        #item['title'] = item['title'].decode('utf8')
#        seg_list = jieba.cut(item['title'])
#        item['keywords'] = ", ".join(seg_list)
        #tmp = ", ".join(dict.user_dict.keys())
        #user_dict = tmp.decode('utf8')
        #user_dict = user_dict.split(", ")
        #print ", ".join(user_dict)
        #print ", ".join(seg_list)
        #print list(set(user_dict) & set(seg_list)) 

        query = self.db.runInteraction(self._conditional_insert, item)
 
        query.addErrback(self.handle_error)
        return item
 
    def _conditional_insert(self, tx, item):
        if item.get('title'):
            tx.execute(\
                "insert into tbl_video (title,type,url,urlmd5,pic,createtime,addtime,site)"
                "values (%s,%s,%s,%s,%s,%s,%s,%s) ON DUPLICATE KEY UPDATE addtime=addtime",
                (
				item['title'],
				item['type'],
				item['url'],
				item['urlmd5'],
				item['pic'],
				item['createtime'],
				item['addtime'],
				item['site']
				)
            )

    def handle_error(self, e):
        log.err(e)


