#encoding=utf-8
from scrapy.spider import BaseSpider
import json,time
from nbavideo.items import NbavideoItem
import hashlib

class NbavideoSpider(BaseSpider):
    name = "nbavideo"
    allowed_domains = ["sina.com","sina.com.cn"]
    start_urls = [
        "http://api.roll.news.sina.com.cn/zt_list?channel=sports&cat_3=video&cat_1=lq-nba&tag=1&show_ext=1&show_all=1&show_cat=1&format=json&show_num=20",
    ]

    def parse(self, response):

        json_result = json.loads(response.body)
        video_box = json_result['result']['data']
        #print s['result']['data'][0]['id']

        items = []
        for video in video_box:
			item = NbavideoItem()
			item['title'] = video['title']
			item['type'] = video['cat_4_name']
			item['url'] = video['url']
			item['urlmd5'] = hashlib.md5(item['url']).hexdigest().lower()
			item['pic'] = video['img']
			item['createtime'] = video['createtime']
			item['addtime'] = time.strftime("%Y-%m-%d %H:%M:%S", time.localtime(time.time()))
			item['site'] = 'sina.com.cn'
#			if  in item['title'] or '视频录播' in item['title']:
#			    item['title'] =  item['title'].replace('视频','比赛')
#			else:
#			    item['title'] =  item['title'][item['title'].find('-') + 1:]
#			item['url'] = main_info.select('a/@href').extract()[0]
#			item['urlmd5'] = hashlib.md5(item['url']).hexdigest().lower()
#			item['pic'] = main_info.select('a/img/@src').extract()[0]
#			
#			item['vyear'] = time.strftime("%Y", time.localtime(time.time()))
#			item['vdate'] = video.select('div[@class="c_txt"]/div[@class="c_info"]/text()').extract()[0]
#			item['vdate'] = item['vdate'][0:+item['vdate'].find(' ')]

			items.append(item)
#
        items.reverse()
        return items

