<?php

class TestCommand extends CConsoleCommand {

    public function actionUpdateChannel() {

        $siteAPI = array(
            '56.com' => 'http://api.56.com/api/bdvideo.php?url=',
            'ku6.com' => 'http://recv.ku6.com/baiduapi.htm?url=',
            'ifeng.com' => 'http://dyn.v.ifeng.com/baiduVideo/getVideoInfo?vlink=',
            'pptv.com' => 'http://v.pptv.com/baidu_api/play.json?link=',
            'iqiyi.com' => 'http://expand.video.qiyi.com/api/fb?apiKey=cf18bf9df4124ca5b98859b9f94995c6&playurl=',
            'tangdou.com' => 'http://www.tangdou.com/api/baiduvideo.php?url=',
            'yinyuetai.com' => 'http://api.yinyuetai.com/api/baidu/short-video?currentPage=',
            'kankan.com' => 'http://api.kankan.com/for_baidu.php?url=',
            'baomihua.com' => 'http://video.baomihua.com/play2/interface/getbaiduvideo.ashx?vId=0&jsoncallback=12&url=',
            'aipai.com' => 'http://www.aipai.com/api/share_video.php?sid=baidu&url=',
            'sina.com.cn' => 'http://video.sina.com.cn/interface/videoListForBaidu.php?url=',
            'kankanews.com' => 'http://interface.kankanews.com/kkapi/baidu/newvideo.php?m=GET_VIDEO_CONTENT&url=',
            'v1.cn' => 'ynews.v1.cn/news/rec-news-fbd/?url=',
            'pps.tv' => ' http://i.ipd.pps.tv/web/getBDVideoRec.php?url=',
            'boosj.com' => ' http://type.boosj.com/forbaidu.html?url=',
            'letv.com' => 'http://xml.coop.letv.com/forbaidu?example=',
        );
        $db = Yii::app()->db;
        $channelId = array(25,5,3,6,27,7,4,10,8,2,9,29);
        $channelName = array(
            '美女' => 25,
            '搞笑' => 5,
            '娱乐' => 3,
            '音乐' => 6,
            '舞蹈' => 27,
            '生活' => 7,
            '体育' => 4,
            '资讯' => 10,
            '原创' => 8,
            '时尚' => 2,
            '游戏' => 9,
            '戏曲' => 29,
        );
        $video = $db->createCommand('select video_id,source_detail_link from tbl_short_video_meta where channel_id=28')->queryAll();
        echo "total count: " . count($video) . "\n";
        $updated = 0;
        foreach ($video as $v) {
            $flag = true;
            foreach ($siteAPI as $siteName => $apiUrl) {
                if (strpos($v['source_detail_link'], $siteName) !== false) {
                    $url = $apiUrl . $v['source_detail_link'];
                    $str = file_get_contents($url);
                    if ($arr = json_decode($str, true)) {
                        if (isset($arr['streamType'])) {
                            if (isset($channelName[$arr['streamType']])) {
                                echo "Y\t" . $arr['streamType'] . "\t" . $arr['title'] . "\t$updated\n";
                                $db->createCommand()->update(array('channel_id'=>$channelName[$arr['streamType']]), 'video_id='.$v['video_id']);
                                $updated++;
                            }
                            else {
                                echo "N\t" . $arr['streamType'] . "\t" . $arr['title'] . "\t$url\n";
                            }
                        }
                        else {
                            echo "N\tno streamType\t" . $url . "\n";
                        }
                    }
                    else {
                        echo "E\tfailed to get result from API\t" . $url . "\n";
                    }
                }
            }
        }
    }


    public function actionFilterTitle() {
        $pattern = array(
            '/-\d{8}-/',
            '/\d{8}/',
            '/\d{4}-\d{2}-\d{2}/',
            '/13[0-1][0-9][0-3][0-9]/',
            '/\[.*\]/',
            '/\(.*\)/',
            '/（.*）/',
            '/【.*】/',
            '/\[.*$/',
            '/\(.*$/',
            '/（.*$/',
            '/【.*$/',
            '/在线观看/',
            '/高清/',
            '/高清版/',
            '/在线播放/',
            '/56出品/',
            '/56娱乐快报/',
            '/56音乐下午茶/',
            '/56视频/',
            '/军情解码/',
            '/现场快报/',
            '/新闻现场/',
            '/子夜快车/',
            '/^东方新闻/',
            '/^看东方/',
            '/^东方午新闻/',
            '/^正午30分/',
            '/^直播港澳台/',
        );
        $replace = array_fill(0, count($pattern), '');
        $title = Yii::app()->db->createCommand("select video_title from tbl_short_video_meta order by final_score desc limit 1000")->queryColumn();
        foreach ($title as $str) {
            $filtered = trim(preg_replace($pattern, $replace, $str));
            if ($str != $filtered) {
                echo "$str\n$filtered\n\n";
            }
        }
    }


    public function actionParseCsv() {
        if (($handle = fopen("/home/video/huyichuan/temp/short_video_genome_exp_new.csv", "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $fatherId = $data[1];
                $titleChs = $data[2];
                $count = Yii::app()->db->createCommand("select count(*) from tbl_genome where father_id=$fatherId and title_chs='$titleChs'")->queryScalar();
                if ($count==0) {
                    $genome = new Genome();
                    $genome->father_id = $fatherId;
                    $genome->title_chs = $titleChs;
                    $genome->level = 1;
                    $genome->save();
                    $key = new GenomeKeyword();
                    $key->genome_id = $genome->id;
                    $key->keyword = $data[3];
                    $key->save();
                    echo $key->keyword . "\t" . count($key->getVideoId()) . "\n";
                }
            }
            fclose($handle);
        }

    }

    public function actionInterleave($id=0) {
        if ($id > 0) {
            $genome = Genome::model()->findByPk($id);
            $original_vid = $genome->getVideoId(1,50);
        }
        else {
            $original_vid = Yii::app()->db->createCommand("select distinct video_id from tbl_video_tokenize where keyword='李宗瑞'")->queryColumn();
        }

        if (!$original_vid) {
            return;
        }

        $db = Yii::app()->db;

        $rows = $db->createCommand("select video_id,site_id,final_score from tbl_short_video_meta where video_id in (".implode(",", $original_vid).") order by final_score desc")->queryAll();
        $sorted_score = array();
        $score_by_vid = array();
        $vid_by_site = array();
        foreach ($rows as $r) {
            echo $r['video_id'] ."\t". $r['site_id'] ."\t". $r['final_score'] . "\n";
            $score_by_vid[$r['video_id']] = $r['final_score'];
            $site_by_vid[$r['video_id']] = $r['site_id'];
            $sorted_score[] = $r['final_score'];
            $vid_by_site[$r['site_id']][] = $r['video_id'];
        }

        echo "\n\n";

        $interleaved_vid = array();
        for ($vid_by_site; count($vid_by_site); $vid_by_site = array_filter($vid_by_site)) {
            foreach ($vid_by_site as &$vid) {
                $interleaved_vid[] = array_shift($vid);
            }
        }

        for ($i=0; $i<count($interleaved_vid); $i++) {
            if ($score_by_vid[$interleaved_vid[$i]] != $sorted_score[$i]) {
                $score_by_vid[$interleaved_vid[$i]] = $sorted_score[$i];
            }
        }

        arsort($score_by_vid);

        foreach ($score_by_vid as $vid=>$score) {
            echo $vid ."\t". $site_by_vid[$vid] ."\t". $score . "\n";
        }

    }

    public function actionSiteSubmission() {

        // get all channels
        $channels = Channel::model()->findAll(array(
            "select" => "channel_id,title_chs",
        ));

        foreach ($channels as $c) {
            // site submission
            if (isset(Yii::app()->params['siteSubmission'][$c->channel_id])) {
                foreach (Yii::app()->params['siteSubmission'][$c->channel_id] as $row) {
                    print_r($row);
                    $vid = Yii::app()->db->createCommand("select video_id from tbl_video_tokenize where tokenize_type='subchannel' and keyword='".$row['subchannel']."'")->queryColumn();
                    if ($vid && $row['tag']) {
                        $ret = Yii::app()->db->createCommand("select video_id from tbl_video_tokenize where keyword='".$row['tag']."'")->queryColumn();
                        $vid = array_intersect($vid, $ret);
                    }
                    if ($vid && $row['site_id']) {
                        $vid = Yii::app()->db->createCommand("select video_id from tbl_short_video_meta where site_id=".$row['site_id']." and video_id in (".implode(",",$vid).") and flag=1 order by final_score desc")->queryColumn();
                        if (count($vid) >= 25) {
                            echo "{$c->title_chs}\t{$row['tag']}\t99999\t".implode(",",$vid)."\n";
                        }
                    }
                }
            }
        }

    }


}

?>
