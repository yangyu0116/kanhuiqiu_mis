# Define here the models for your scraped items
#
# See documentation in:
# http://doc.scrapy.org/topics/items.html

from scrapy.item import Item, Field

class NbavideoItem(Item):
    title = Field()
    type = Field()
    url = Field()
    urlmd5 = Field()
    pic = Field()
    createtime = Field()
    addtime = Field()
    site = Field()
    source_id = Field()