#encoding=utf-8
from scrapy.spider import BaseSpider
from scrapy.selector import HtmlXPathSelector

from nbavideo.items import NbavideoItem

import time,hashlib

class NbavideoSpider(BaseSpider):
    name = "nbavideo"
    allowed_domains = ["sina.com","sina.com.cn"]
    start_urls = [
        "http://roll.sports.sina.com.cn/s_nbavideo_video_big/index.shtml",
    ]

    def parse(self, response):
		#f=open('nbavideo.json','w')
		#f.write('')
		#f.flush()
		#f.close()

		hxs = HtmlXPathSelector(response)
		video_box = hxs.select('//div[@class="videoBox"]')
		items = []
		for video in video_box:
			item = NbavideoItem()
			main_info = video.select('div[@class="c_pic"]')
			#title = main_info.select('a/img/@alt').extract()[0]
			#title = eval("u'%s'" % (title))
			#print title
			item['title'] = main_info.select('a/img/@alt').extract()[0]#title.encode("utf8")
			item['title'] = item['title'].encode('utf8')
			if '视频集锦' in item['title'] or '视频录播' in item['title']:
			    item['title'] =  item['title'].replace('视频','比赛')
			else:
			    item['title'] =  item['title'][item['title'].find('-') + 1:]
			item['url'] = main_info.select('a/@href').extract()[0]
			item['urlmd5'] = hashlib.md5(item['url']).hexdigest().lower()
			item['pic'] = main_info.select('a/img/@src').extract()[0]
			
			item['vyear'] = time.strftime("%Y", time.localtime(time.time()))
			item['vdate'] = video.select('div[@class="c_txt"]/div[@class="c_info"]/text()').extract()[0]
			item['vdate'] = item['vdate'][0:+item['vdate'].find(' ')]

			item['addtime'] = time.strftime("%Y-%m-%d %H:%M:%S", time.localtime(time.time()))
			item['site'] = 'sina.com.cn'

			items.append(item)

		items.reverse()
		
		#for i in items:
			#if '10' not in i['title']:
			#items.remove(i)


		return items

