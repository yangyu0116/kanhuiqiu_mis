<?php

class StatsController extends Controller {

    public $layout = '//layouts/column1';

    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        );
    }

    public function accessRules() {
        return array(
            array('allow',
                'actions' => array('topKeywords', 'avgPlayNum'),
                'users' => array('admin'),
            ),
            array('deny',
                'users' => array('*'),
            ),
        );
    }

    public function actionTopKeywords($d=7) {
        
        if(intval($d) > 30) {
            $day = 30;
        }
        elseif (intval($d) > 0) {
            $day = intval($d);
        }
        else {
            $day = 7;
        }
        
        $db = Yii::app()->db;

        // find video id from last $d days
        $start_time = time() - 3600 * 24 * intval($day);
        $start_time_str = date("Ymd", $start_time);

        $vid = $db->createCommand("select video_id from tbl_short_video_meta where pub_date>" . $start_time_str)->queryColumn();

        // keywords already matched with genome
        $genome_keywords = $db->createCommand("select distinct keyword from tbl_genome_keyword")->queryColumn();
        
        // stopwords
        $stopwords = $db->createCommand("select stopword from tbl_stopword")->queryColumn();

        $conditions = array();
        $conditions["channel"] = "video_id in (" . implode(",", $vid) . ") and tokenize_type='channel'";
        $conditions["subchannel"] = "video_id in (" . implode(",", $vid) . ") and tokenize_type='subchannel'";
        $conditions["other"] = "video_id in (" . implode(",", $vid) . ") and tokenize_type<>'channel' and tokenize_type<>'subchannel'";

        $type = array("channel", "subchannel", "other");
        $results = array();
        foreach ($type as $t) {
            $sql = "select keyword,count(*) as count from tbl_video_tokenize where " . $conditions[$t] . " group by keyword order by count desc limit 300";
            $rows = $db->createCommand($sql)->queryAll();
            foreach ($rows as $r) {
                if (!in_array($r["keyword"], $stopwords)) {
                    $temp = array();
                    $temp["id"] = $r["keyword"]."_".$t."_".$d;
                    $temp["keyword"] = $r["keyword"];
                    $temp["count"] = $r["count"];
                    $temp["is_genome"] = in_array($r["keyword"], $genome_keywords) ? 1 : 0;
                    $results[$t][] = $temp;                    
                }
            }
        }
        
        $this->render('top_keywords', array(
            'day' => $day,
            'results' => $results
        ));
    }
    
    public function actionAvgPlayNum() {
        $model = new SiteAvgPlayNum('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['SiteAvgPlayNum']))
            $model->attributes = $_GET['SiteAvgPlayNum'];

        $this->render('site_avg_play_num', array(
            'model' => $model,
        ));
    }    

}

?>
