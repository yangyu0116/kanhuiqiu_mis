<?php

class CrontabCommand extends CConsoleCommand {

    public $siteWhiteList;

    public function init() {
        // initialize site whitelist
        $this->siteWhiteList = array();
        if ($ret = file_get_contents('/home/video/webroot/short-video-data/output/site_whitelist.txt')) {
            $ret = iconv("GBK", "UTF-8", $ret);
            $siteDomain = explode("\n", $ret);
            foreach ($siteDomain as $s) {
                $ret = explode(".", $s);
                $domain = $ret[0];
                if ($domain != 'baomihua' && $siteId = Yii::app()->db->createCommand("select site_id from tbl_site where site_py='{$domain}'")->queryScalar()) {
                    $this->siteWhiteList[] = $siteId;
                }
            }
        }
        if (empty($this->siteWhiteList)) {
            $this->siteWhiteList = Yii::app()->db->createCommand("select site_id from tbl_site")->queryColumn();
        }
    }

    public function actionTest() {
        print_r($this->siteWhiteList);
    }

    /**
     * Update final score for all videos
     */
    public function actionUpdateVideoScore() {

        // import hot videos from video.baidu.com
        $this->importHotVideoFromIndex();

        // update hot value for videos in the past 3 weeks
        $start_time = time() - 3600*24*21;
        $start_date = intval(date("Ymd", $start_time));

        // set final score to zero for videos older than 2 weeks
        Yii::app()->db->createCommand()->update('tbl_short_video_meta', array('final_score'=>1), 'final_score<>1 and pub_date<='.$start_date);

        // compute final score
        $this->computeVideoFinalScore($start_date);

        // remove redundant video
        $this->removeRedundantVideo();

    }

    /**
     * Import hot videos from video.baidu.com
     */
    public function importHotVideoFromIndex() {
        // find site_id and site_py relationship
        $rows = Yii::app()->db->createCommand("select site_id,site_py from tbl_site where site_py<>'kankan'")->queryAll();
        $sites = array();
        foreach ($rows as $r) {
            $sites[$r['site_id']] = $r['site_py'];
        }
        // channels to import
        $channels = array(
            "ftp://m1-video-preview0.m1.baidu.com:/home/video/mis/video_index_2.txt" => 'remen',
        );
        foreach ($channels as $file_url=>$channel_py) {
            if ($channel_id = Yii::app()->db->createCommand("select channel_id from tbl_channel where title_py='$channel_py'")->queryScalar()) {
                $channels[$file_url] = $channel_id;
            }
            else {
                unset($channels[$file_url]);
            }
        }
        // loop over all channels
        foreach ($channels as $file_url=>$channel_id) {
            if ($ret = file_get_contents($file_url)) {
                $ret = iconv("GBK", "UTF-8", $ret);
                $rows = explode("\n", $ret);
                $videos = array();
                foreach ($rows as $row) {
                    if ($ret = $this->parseHotVideo($row)) {
                        $videos[] = $ret;
                    }
                }
                // sort videos by weight
                usort($videos, array($this, "compareWeight"));
                $i = 0;
                foreach ($videos as $v) {
                    if ($site_id = $this->parseSite($v["source_detail_link"], $sites)) {
                        $v["site_id"] = $site_id;
                        $v["channel_id"] = $channel_id;
                        unset($v["weight"]);
                        $this->updateHotVideo($v);
                    }
                    $i++;
                    if ($i==10)
                        break;
                }
            }
        }
    }

    /**
     * Import a hot video
     * @param $v array for the hot video to be imported
     */
    private function updateHotVideo($v) {
        $row = Yii::app()->db->createCommand("select video_id,simg_url from tbl_short_video_meta where source_detail_link='{$v['source_detail_link']}'")->queryRow();
        // if exists, then update; otherwise, insert.
        if ($row) {
            if ($row['simg_url']!=$v['simg_url']) {
                Yii::app()->db->createCommand()->update("tbl_short_video_meta", $v, "video_id=".$row['video_id']);
            }
        }
        else {
            Yii::app()->db->createCommand()->insert("tbl_short_video_meta", $v);
            $vid = Yii::app()->db->createCommand("select video_id from tbl_short_video_meta where source_detail_link='{$v['source_detail_link']}'")->queryScalar();
            if ($vid) {
                VideoExist::set_exist($v['source_detail_link'], $vid);
            }
        }
    }

    /**
     * Parse hot video meta data
     * @param $str string read from file
     * @return array hot video
     */
    private function parseHotVideo($str) {
        $arr = explode("\t", $str);
        if (isset($arr[2]) && isset($arr[3]) && isset($arr[4]) && isset($arr[11]) && isset($arr[25])) {
            return array(
                "video_title" => $arr[2],
                "source_detail_link" => $arr[3],
                "simg_url" => $arr[4],
                "add_time" => time(),
                "duration" => $this->parseDuration($arr[11]),
                "weight" => $arr[25],
                "source" => 2,
                "flag" => 0,
            );
        }
        return array();
    }

    /**
     * Find site_id according to video url
     * @param $url video url
     * @param $sites array of site_id and site_py relationship
     * @return int
     */
    private function parseSite($url, $sites) {
        foreach ($sites as $site_id=>$site_py) {
            if (strpos($url, "$site_py.com") !== false) {
                return $site_id;
            }
        }
        return 0;
    }

    /**
     * Convert duation MM:SS to seconds
     * @param $str duration in the format of MM:SS
     * @return int duration in seconds
     */
    private function parseDuration($str) {
        $arr = explode(":", $str);
        $multi = 1;
        $seconds = 0;
        for ($i=count($arr)-1; $i>=0; $i--) {
            $seconds += $multi * intval($arr[$i]);
            $multi *= 60;
        }
        return $seconds;
    }

    /**
     * Compare two arrays by the element "weight"
     * @param $a
     * @param $b
     * @return int
     */
    static function compareWeight($a, $b) {
        if ($a['weight'] == $b['weight']) {
            return 0;
        }
        return ($a['weight'] < $b['weight']) ? -1 : 1;
    }

    /**
     * Compute final score for recent videos
     * @param $start_date
     */
    public function computeVideoFinalScore($start_date) {
        // get average play nums for all sites and channels
        $site_play_nums = array();
        $site_incremental_play_nums = array();
        $channels = Yii::app()->db->createCommand("select channel_id from tbl_channel")->queryColumn();
        foreach ($channels as $channel_id) {
            $rows = Yii::app()->db->createCommand("select site_id,avg(play_nums) as avg_play_nums from tbl_short_video_meta where channel_id=$channel_id and pub_date>$start_date group by site_id")->queryAll();
            foreach ($rows as $row) {
                $site_play_nums[$row['site_id']][$channel_id] = intval($row['avg_play_nums']);
            }

            $rows = Yii::app()->db->createCommand("select site_id,avg((play_nums-last_play_nums)/(update_time-last_update_time)*3600) as avg_incremental_play_nums from tbl_short_video_meta where play_nums>last_play_nums and update_time>last_update_time and channel_id=$channel_id and pub_date>$start_date group by site_id")->queryAll();
            foreach ($rows as $row) {
                $site_incremental_play_nums[$row['site_id']][$channel_id] = intval($row['avg_incremental_play_nums']);
            }
        }

        $rows = Yii::app()->db->createCommand("select * from tbl_short_video_meta where pub_date>$start_date")->queryAll();
        foreach ($rows as $row) {
            $pub_year = intval($row["pub_date"]/10000);
            $pub_month = intval(($row["pub_date"] - $pub_year*10000) / 100);
            $pub_day = intval(($row["pub_date"] - $pub_year*10000 - $pub_month*100));
            $pub_timestamp = mktime(0, 0, 0, $pub_month, $pub_day, $pub_year);

            // compute score_1 based on normalized play nums
            if ($site_play_nums[$row['site_id']][$row['channel_id']]>0) {
                $normalized_play_nums = round($row["play_nums"] / $site_play_nums[$row['site_id']][$row['channel_id']], 2);
            }
            else {
                $normalized_play_nums = 0;
            }
            $score_1 = intval( 7500 * (log(1 + $normalized_play_nums, 2) - (time() - $pub_timestamp) / (3600*24)) );

            // compute score_2 based on normalized incremental play nums
            $normalized_incremental_play_nums = 0;
            if ($row['update_time']>0 && $row['last_update_time']>0 && $row['update_time']>$row['last_update_time'] && $row['play_nums']>$row['last_play_nums']) {
                $incremental_play_nums = intval( ($row['play_nums'] - $row['last_play_nums']) / ($row['update_time'] - $row['last_update_time']) * 3600 );
                if ($site_incremental_play_nums[$row['site_id']][$row['channel_id']] > 0) {
                    $normalized_incremental_play_nums = round( $incremental_play_nums / $site_incremental_play_nums[$row['site_id']][$row['channel_id']], 2);
                }
            }
            $score_2 = intval( 2500 * log(1 + $normalized_incremental_play_nums, 2) );

            // compute final score by adding score_1 and score_2
            $final_score = $score_1 + $score_2 + 100000;
            if ($final_score <= 0) {
                $final_score = 1;
            }

            Yii::app()->db->createCommand()->update("tbl_short_video_meta", array("final_score" => $final_score), "video_id=" . $row["video_id"]);
        }
    }

    /**
     * Adjust video final score to balance over sites
     */
    private function interleaveVideoBySite($original_vid) {

        if (!$original_vid) {
            return array();
        }

        // only output site from whitelist
        $rows = Yii::app()->db->createCommand("select video_id,site_id,final_score from tbl_short_video_meta where video_id in (".implode(",", $original_vid).") and site_id in (".implode(",", $this->siteWhiteList).") order by final_score desc")->queryAll();
        $sorted_score = array();
        $score_by_vid = array();
        $vid_by_site = array();
        foreach ($rows as $r) {
            $score_by_vid[$r['video_id']] = $r['final_score'];
            $sorted_score[] = $r['final_score'];
            $vid_by_site[$r['site_id']][] = $r['video_id'];
        }

        $interleaved_vid = array();
        for ($vid_by_site; count($vid_by_site); $vid_by_site = array_filter($vid_by_site)) {
            foreach ($vid_by_site as &$vid) {
                $interleaved_vid[] = array_shift($vid);
            }
        }

        for ($i=0; $i<count($interleaved_vid); $i++) {
            $updated_score = $sorted_score[$i]==1 ? $sorted_score[$i]+count($interleaved_vid)-$i : $sorted_score[$i];
            if ($score_by_vid[$interleaved_vid[$i]] != $updated_score) {
                Yii::app()->db->createCommand()->update('tbl_short_video_meta', array('final_score'=>$updated_score), 'video_id='.$interleaved_vid[$i]);
            }
        }

        return $interleaved_vid;

    }

    /**
     * Adjust video final score
     */
    public function adjustVideoRanking() {
        $channels = Channel::model()->findAll(array(
            "select" => "channel_id,title_chs",
        ));
        $stopwords = Yii::app()->db->createCommand("select stopword from tbl_stopword")->queryColumn();

        // balance site frequency in top videos
        foreach ($channels as $c) {
            if (!in_array($c->title_chs, $stopwords)) {
                $sites = Yii::app()->db->createCommand("select distinct site_id from tbl_short_video_meta where site_id>0 and channel_id=".$c->channel_id." and play_nums>0 order by final_score desc limit 500")->queryColumn();
                if (count($sites) > 1) {
                    $top_video_count_by_site = intval(200 / count($sites));
                    $video_id_by_site = array();
                    $top_video_count = 0;
                    foreach ($sites as $site_id) {
                        $video_id_by_site[$site_id] = Yii::app()->db->createCommand("select video_id from tbl_short_video_meta where channel_id=".$c->channel_id." and site_id=".$site_id." and play_nums>0 order by final_score desc limit $top_video_count_by_site")->queryColumn();
                        $top_video_count += count($video_id_by_site[$site_id]);
                    }
                    $top_video_score = Yii::app()->db->createCommand("select final_score from tbl_short_video_meta where channel_id=".$c->channel_id." order by final_score desc limit ".$top_video_count)->queryColumn();
                    $min_video_score = $top_video_score[count($top_video_score)-1];
                    if (intval($min_video_score)<=0) {
                        $min_video_score = 1;
                    }
                    Yii::app()->db->createCommand()->update("tbl_short_video_meta", array("final_score"=>$min_video_score), "channel_id={$c->channel_id} and final_score>$min_video_score");
                    $index = 0;
                    $flag = true;
                    while ($flag && $index<$top_video_count) {
                        $flag = false;
                        shuffle($sites);
                        foreach ($sites as $site_id) {
                            if (isset($video_id_by_site[$site_id][0])) {
                                $flag = true;
                                Yii::app()->db->createCommand()->update("tbl_short_video_meta", array("final_score"=>$top_video_score[$index]), "video_id=".$video_id_by_site[$site_id][0]);
                                unset($video_id_by_site[$site_id][0]);
                                $video_id_by_site[$site_id] = array_values($video_id_by_site[$site_id]);
                                $index++;
                            }
                        }
                    }
                }
            }
        }

        // move high quality videos to top
        $yesterday_date = date('Ymd', time()) - 2;
        $top_threshold = 50;
        foreach ($channels as $c) {

//            $sites = Yii::app()->db->createCommand("select distinct site_id from tbl_short_video_meta where channel_id={$c->channel_id} and source=2 and pub_date>=$yesterday_date")->queryColumn();
//            $vid_by_site = array();
//            foreach ($sites as $sid) {
//                $vid_by_site[$sid] = Yii::app()->db->createCommand("select video_id from tbl_short_video_meta where site_id={$sid} and channel_id={$c->channel_id} and source=2 and pub_date>=$yesterday_date order by final_score desc limit {$top_threshold}")->queryColumn();
//            }
//            $vid_sorted = array();
//            $stop = false;
//            while (!$stop) {
//                $stop = true;
//                foreach ($sites as $sid) {
//                    if ($vid_by_site[$sid]) {
//                        $vid_sorted[] = array_shift($vid_by_site[$sid]);
//                        $stop = false;
//                    }
//                }
//            }
//            $max_score = Yii::app()->db->createCommand("select max(final_score) from tbl_short_video_meta where channel_id={$c->channel_id}")->queryScalar();
//            $i = 0;
//            foreach ($vid_sorted as $vid) {
//                $score = $max_score + count($vid_sorted) - $i;
//                Yii::app()->db->createCommand()->update('tbl_short_video_meta', array('final_score'=>$score), "video_id=$vid");
//                $i += 1;
//            }

//            $sites = Yii::app()->db->createCommand("select distinct site_id from tbl_short_video_meta where channel_id={$c->channel_id} and source=1 and pub_date>=$yesterday_date")->queryColumn();
//            $vid_by_site = array();
//            foreach ($sites as $sid) {
//                $vid_by_site[$sid] = Yii::app()->db->createCommand("select video_id from tbl_short_video_meta where site_id={$sid} and channel_id={$c->channel_id} and source=1 and pub_date>=$yesterday_date order by final_score desc limit {$top_threshold}")->queryColumn();
//            }
//            $vid_sorted = array();
//            $stop = false;
//            while (!$stop) {
//                $stop = true;
//                foreach ($sites as $sid) {
//                    if ($vid_by_site[$sid]) {
//                        $vid_sorted[] = array_shift($vid_by_site[$sid]);
//                        $stop = false;
//                    }
//                }
//            }
//            $max_score = Yii::app()->db->createCommand("select max(final_score) from tbl_short_video_meta where channel_id={$c->channel_id}")->queryScalar();
//            $i = 0;
//            foreach ($vid_sorted as $vid) {
//                $score = $max_score + count($vid_sorted) - $i;
//                Yii::app()->db->createCommand()->update('tbl_short_video_meta', array('final_score'=>$score), "video_id=$vid");
//                $i += 1;
//            }
        }
    }

    /**
     * Remove redundant videos
     */
    public function removeRedundantVideo() {
        $channels = Yii::app()->db->createCommand("select channel_id from tbl_channel where channel_id<19")->queryColumn();
        foreach ($channels as $cid) {
            $sql = "select video_id from tbl_short_video_meta where channel_id=$cid order by final_score desc limit 200";
            $vid = Yii::app()->db->createCommand($sql)->queryColumn();
            $vid_str = implode(",", $vid);
            $sql = "select min(final_score) from tbl_short_video_meta where video_id in (" . $vid_str . ")";
            $min_score = Yii::app()->db->createCommand($sql)->queryScalar();
            $sql = "select keyword,count(*) as count from tbl_video_tokenize where video_id in (" . $vid_str . ") group by keyword order by count desc";
            $keywords = Yii::app()->db->createCommand($sql)->queryAll();
            foreach ($keywords as $k) {
                if ($k['count']>4) {
                    $key = addslashes($k["keyword"]);
                    $sql = "select count(distinct video_id) as count from tbl_video_tokenize where keyword='".$key."'";
                    $count = Yii::app()->db->createCommand($sql)->queryScalar();
                    $normalized_count = $k['count'] / $count;
                    if ($normalized_count > 0.1) {
                        $sql = "select distinct video_id from tbl_video_tokenize where video_id in (" . $vid_str . ") and keyword='" . $key . "'";
                        $kvid = Yii::app()->db->createCommand($sql)->queryColumn();
                        $sql = "select video_id,video_title,site_id,pub_date,final_score from tbl_short_video_meta where video_id in (" . implode(",", $kvid) . ") order by final_score desc";
                        $kvideo = Yii::app()->db->createCommand($sql)->queryAll();
                        $i = 0;
                        foreach ($kvideo as $kv) {
                            $i++;
                            if ($i == 1) {
                                continue;
                            }
                            else {
                                $adjusted_score = $min_score - rand(100,500)*$i;
                                $adjusted_score = $adjusted_score<0 ? 0 : $adjusted_score;
                                Yii::app()->db->createCommand()->update('tbl_short_video_meta', array('final_score'=>$adjusted_score), 'video_id='.$kv['video_id']);
                            }
                        }
                    }
                }
                else {
                    break;
                }
            }
        }
    }

    /**
     * Update hot values for all genomes
     */
    public function actionUpdateGenomeScore() {
        $genomes = Genome::model()->findAll();
        foreach ($genomes as $g) {
            $vid = $g->getVideoId(1,2000);
            if ($vid) {
                // compute hot value
                $vid_str = implode(",", array_unique($vid));
                $sql = "select avg(final_score) as avg from tbl_short_video_meta where video_id in ($vid_str) and final_score>1";
                $score = intval(Yii::app()->db->createCommand($sql)->queryScalar());
                $sql = "select count(*) as count from tbl_short_video_meta where video_id in ($vid_str) and final_score>1";
                $count = intval(Yii::app()->db->createCommand($sql)->queryScalar());
                // if video count is less than 50, penalize the score
                if ($count < 50) {
                    $score = $score * $count / 50;
                }
                // update hot value
                $g->hot_value = $score;
                $g->count = count($vid);
                $g->save();
            }
        }
    }

    /**
     * Update average play number for all sites and channels
     */
    public function actionUpdateSiteAvgPlayNum() {
        Yii::app()->db->createCommand()->delete("tbl_site_avg_play_num");
        
        // get distinc channel id and site id
        $channels = Yii::app()->db->createCommand("select distinct channel_id from tbl_short_video_meta")->queryColumn();
        $sites = Yii::app()->db->createCommand("select distinct site_id from tbl_short_video_meta")->queryColumn();

        foreach ($sites as $sid) {

            // update avg play num for videos in the entire site
            $temp = Yii::app()->db->createCommand("select avg(play_nums) as avg from tbl_short_video_meta where site_id=$sid and play_nums>0")->queryScalar();
            $avg_play_num = $temp ? $temp : 0;
            $column = array("site_id" => $sid, "channel_id" => 0, "avg_play_num" => $avg_play_num, "update_time" => time());
            Yii::app()->db->createCommand()->insert("tbl_site_avg_play_num", $column);

            // update avg play num for videos in each channel
            foreach ($channels as $cid) {
                $temp = Yii::app()->db->createCommand("select avg(play_nums) as avg from tbl_short_video_meta where site_id=$sid and channel_id=$cid and play_nums>0")->queryScalar();
                $avg_play_num = $temp ? $temp : 0;
                $column = array("site_id" => $sid, "channel_id" => $cid, "avg_play_num" => $avg_play_num, "update_time" => time());
                Yii::app()->db->createCommand()->insert("tbl_site_avg_play_num", $column);
            }
        }
    }

    private function checkVideoCount() {
        $count = Yii::app()->db->createCommand("select count(*) from tbl_short_video_meta where flag=2")->queryScalar();
        $totalCount = Yii::app()->db->createCommand("select count(*) from tbl_short_video_meta")->queryScalar();
        if ($count/$totalCount > 0.1) {
            mail("huyichuan@baidu.com", "Warning: flag=2 video count too many", "flag=2 video count = $count");
            Yii::app()->db->createCommand()->update('tbl_short_video_meta', array('flag'=>1), "limg_url is not null and limg_url<>'' and flag=2");
        }
        $count = Yii::app()->db->createCommand("select count(*) from tbl_short_video_meta where flag=1")->queryScalar();
        if ($count<100000) {
            mail("huyichuan@baidu.com", "Warning: flag=1 video count too small", "flag=1 video count = $count");
            return false;
        }
        return true;
    }

    /**
     * Generate hot genomes
     */
    public function actionOutput() {

        $ctime = time();
        $year = date("Y", $ctime);
        $month = date("m", $ctime);
        $day = date("d", $ctime);
        $hour = date("H", $ctime);
        $dir = "/home/video/webroot/short-video-data/output/tag/$year$month";
        if (!file_exists($dir)) {
            mkdir($dir, 0755, true);
        }

        $filename = $year.$month.$day.$hour.".txt";
        $fp = fopen("$dir/$filename", 'w');

        // exclude channels
        $exclude_tag = Yii::app()->db->createCommand("select title_chs from tbl_channel")->queryColumn();

        // get top 500 videos
        $vid = Yii::app()->db->createCommand("select video_id from tbl_short_video_meta where flag=1 and site_id in (".implode(",", $this->siteWhiteList).") order by final_score desc limit 500")->queryColumn();
        $vid = $this->interleaveVideoBySite($vid);
        $score = Yii::app()->db->createCommand("select max(hot_value) from tbl_genome")->queryScalar() + 9999;
        fwrite($fp, "热门\t{$score}\t".implode(",",$vid)."\r\n");
        $output_tag[] = "热门";

        // top video search queries
        $top_queries = array(
            '美女写真' => '美女$$写真',
            '美女热舞' => '美女$$热舞',
            '广场舞' => '广场',
            '街舞' => '街舞',
            'DJ舞曲' => '舞曲',
            '广场舞' => '广场舞',
            '儿童舞蹈' => '儿童$$舞蹈',
            '性感热舞' => '性感$$热舞',
            '家庭幽默录像' => '家庭$$搞笑',
            '江南style' => '江南$$style',
            '儿歌' => '儿歌',
            '凤凰传奇' => '凤凰传奇',
            'NBA' => 'NBA',
            '麦迪' => '麦迪',
            '科比' => '科比',
            '郭德纲相声' => '郭德纲$$相声',
            '赵本山小品' => '赵本山$$小品',
            '偷拍' => '偷拍',
            'LOL' => 'LOL$$英雄联盟',
            'CF' => '穿越火线',
            '老湿' => '老湿',
            '鸟叔' => '鸟叔',
            '爱奇艺早班机' => '早班机',
        );
        $max_score = 99999;
        foreach ($top_queries as $query=>$keywords) {
            if (!in_array($query, $exclude_tag)) {
                $key = explode("$$", $keywords);
                $i = 0;
                $vid = array();
                foreach ($key as $k) {
                    $k = addslashes($k);
                    $ret = Yii::app()->db->createCommand("select distinct video_id from tbl_video_tokenize where keyword='$k'")->queryColumn();
                    if ($i==0) {
                        $vid = $ret;
                    }
                    else {
                        $vid = array_intersect($vid, $ret);
                    }
                    $i++;
                }
                if ($vid) {
                    $ret = Yii::app()->db->createCommand("select video_id from tbl_short_video_meta where video_id in (".implode(',', $vid).") and flag=1 and site_id in (".implode(",", $this->siteWhiteList).") order by final_score desc limit 500")->queryColumn();
                    if (count($ret) > 25) {
                        $ret = $this->interleaveVideoBySite($ret);
                        $output_tag[] = $query;
                        fwrite($fp, "{$query}\t{$max_score}\t".implode(",",$ret)."\r\n");
                    }
                }
            }
        }

        // top baidu search queries
        $handle = fopen("/home/video/program/short-video-data/python/data/top_words", 'r');
        if ($handle) {
            while (($buffer = fgets($handle)) !== false) {
                if ($arr = json_decode($buffer, true)) {
                    foreach ($arr as $data) {
                        if (isset($data['seg']) && isset($data['source']) && $data['seg']) {
                            $query = $data['source'];
                            $keywords = $data['seg'];
                            if (!in_array($query, $exclude_tag)) {
                                $i = 0;
                                $vid = array();
                                foreach ($keywords as $k) {
                                    $k = addslashes($k);
                                    $ret = Yii::app()->db->createCommand("select distinct video_id from tbl_video_tokenize where keyword='$k'")->queryColumn();
                                    if ($i==0) {
                                        $vid = $ret;
                                    }
                                    else {
                                        $vid = array_intersect($vid, $ret);
                                    }
                                    $i++;
                                }
                                if ($vid) {
                                    $ret = Yii::app()->db->createCommand("select video_id from tbl_short_video_meta where video_id in (".implode(',', $vid).") and flag=1 and site_id in (".implode(",", $this->siteWhiteList).") order by final_score desc limit 500")->queryColumn();
                                    if (count($ret) > 25) {
                                        $ret = $this->interleaveVideoBySite($ret);
                                        $output_tag[] = $query;
                                        fwrite($fp, "{$query}\t{$max_score}\t".implode(",",$ret)."\r\n");
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        // get top 300 genomes
        $genomes = Genome::model()->findAll(array(
            "select" => "id,title_chs,hot_value",
            "order" => "hot_value desc",
            "condition" => "father_id<>0 and hot_value>0",
            "limit" => 300
        ));

        foreach ($genomes as $g) {
            if (!in_array($g->title_chs, $exclude_tag)) {
                $vid = $g->getVideoId(0, 500, $this->siteWhiteList);
                if ($vid) {
                    $vid = $this->interleaveVideoBySite($vid);
                    fwrite($fp, "{$g->title_chs}\t{$g->hot_value}\t".implode(",",$vid)."\r\n");
                }
            }
        }
        fclose($fp);

        if (!$this->checkVideoCount()) {
            return;
        }

        // copy file
        $output_filename = "/home/video/webroot/short-video-data/output/tag.txt";
        $cmd = "rm -rf $output_filename";
        exec($cmd);
        $cmd = "cp $dir/$filename $output_filename";
        exec($cmd);
    }

    /**
     * Generate hot channels
     */
    public function actionOutputChannel() {

        $ctime = time();
        $year = date("Y", $ctime);
        $month = date("m", $ctime);
        $day = date("d", $ctime);
        $hour = date("H", $ctime);
        $dir = "/home/video/webroot/short-video-data/output/channel/$year$month";
        if (!file_exists($dir)) {
            mkdir($dir, 0755, true);
        }

        $filename = $year.$month.$day.$hour.".txt";
        $fp = fopen("$dir/$filename", 'w');

        // get all channels
        $channels = Channel::model()->findAll(array(
            "select" => "channel_id,title_chs",
        ));
        $stopwords = Yii::app()->db->createCommand("select stopword from tbl_stopword")->queryColumn();

        foreach ($channels as $c) {
            $hot_value = 999999;
            $vid = Yii::app()->db->createCommand("select video_id from tbl_short_video_meta where flag=1 and channel_id={$c->channel_id} and site_id in (".implode(",", $this->siteWhiteList).") order by final_score desc limit 500")->queryColumn();
            $vid = $this->interleaveVideoBySite($vid);
            fwrite($fp, "{$c->title_chs}\t{$hot_value}\t".implode(",",$vid)."\r\n");
        }
        fclose($fp);

        if (!$this->checkVideoCount()) {
            return;
        }

        // copy file
        $output_filename = "/home/video/webroot/short-video-data/output/channel.txt";
        $cmd = "rm -rf $output_filename";
        exec($cmd);
        $cmd = "cp $dir/$filename $output_filename";
        exec($cmd);

        // subchannel
        $dir = "/home/video/webroot/short-video-data/output/subchannel/$year$month";
        if (!file_exists($dir)) {
            mkdir($dir, 0755, true);
        }
        $filename = $year.$month.$day.$hour.".txt";
        $fp = fopen("$dir/$filename", 'w');

        // get all channels
        $channels = Channel::model()->findAll(array(
            "select" => "channel_id,title_chs",
        ));

        foreach ($channels as $c) {
            // site submission
            if (isset(Yii::app()->params['siteSubmission'][$c->channel_id])) {
                foreach (Yii::app()->params['siteSubmission'][$c->channel_id] as $row) {
                    $vid = Yii::app()->db->createCommand("select video_id from tbl_video_tokenize where tokenize_type='subchannel' and keyword='".$row['subchannel']."'")->queryColumn();
                    if ($vid && $row['tag']) {
                        $ret = Yii::app()->db->createCommand("select video_id from tbl_video_tokenize where keyword='".$row['tag']."'")->queryColumn();
                        $vid = array_intersect($vid, $ret);
                    }
                    if ($vid && $row['site_id']) {
                        $vid = Yii::app()->db->createCommand("select video_id from tbl_short_video_meta where site_id=".$row['site_id']." and video_id in (".implode(",",$vid).") and flag=1 order by final_score desc")->queryColumn();
                        if (count($vid) >= 25) {
                            fwrite($fp, "{$c->title_chs}\t{$row['tag']}\t99999\t".implode(",",$vid)."\r\n");
                        }
                    }
                }
            }
            // genome
            $count = 0;
            foreach ($c->genome as $g) {
                if ($count==50) {
                    break;
                }
                if ($g->hot_value > 0) {
                    $vid = $g->getVideoId(1, 500, $this->siteWhiteList);
                    if (count($vid) > 25) {
                        $vid = array_slice($vid, 0, 500);
                        $vid = $this->interleaveVideoBySite($vid);
                        fwrite($fp, "{$c->title_chs}\t{$g->title_chs}\t{$g->hot_value}\t".implode(",",$vid)."\r\n");
                        $count++;
                    }
                }
            }
        }
        fclose($fp);

        // copy file
        $output_filename = "/home/video/webroot/short-video-data/output/subchannel.txt";
        $cmd = "rm -rf $output_filename";
        exec($cmd);
        $cmd = "cp $dir/$filename $output_filename";
        exec($cmd);

    }

}

?>
