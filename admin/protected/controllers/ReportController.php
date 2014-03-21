<?php

class ReportController extends Controller {

    public $layout = '//layouts/column2';

    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        );
    }

    public function accessRules() {
        return array(
            array('allow',
                'actions' => array('daily', 'range', 'email'),
                'users' => array('admin'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    public function actionDaily($date='20130601') {
        $start = strtotime($date);
        $end = $start + 24*3600;
        $condition = "source=1 and add_time>{$start} and add_time<{$end}";

        $db = Yii::app()->db;

        $channel = $db->createCommand("select channel_id,title_chs from tbl_channel")->queryAll();
        $site = $db->createCommand("select site_id,site_py from tbl_site")->queryAll();

        $sql = "select count(*) as count,site_id,channel_id from tbl_short_video_meta where $condition group by channel_id,site_id";
        $ret = $db->createCommand($sql)->queryAll();

        $data = array();
        foreach ($ret as $r) {
            $data[$r['channel_id']][$r['site_id']] = $r['count'];
        }

        $fp = fopen('php://temp', 'w');

        $firstRow = array('');
        foreach ($site as $s) {
            $firstRow[] = $s['site_py'];
        }
        $firstRow[] = 'subtotal';
        fputcsv($fp, $firstRow);

        $i = 0;
        $lastRow = array('title'=>'subtotal');
        foreach ($channel as $c) {
            $row = array(iconv("UTF-8","GBK",$c['title_chs']));
            $subTotal = 0;
            foreach ($site as $s) {
                $count = isset($data[$c['channel_id']][$s['site_id']]) ? $data[$c['channel_id']][$s['site_id']] : 0;
                $subTotal += $count;
                $row[] = $count;
                if (isset($lastRow[$s['site_id']])) {
                    $lastRow[$s['site_id']] += $count;
                }
                else {
                    $lastRow[$s['site_id']] = $count;
                }
            }
            $row[] = $subTotal;
            fputcsv($fp, $row);
            $i++;
        }
        $total = $db->createCommand("select count(*) from tbl_short_video_meta where $condition")->queryScalar();
        fputcsv($fp, array_merge(array_values($lastRow), array($total)));

        $lastRow = array('total');
        $sql = "select count(*) as count,site_id from tbl_short_video_meta where source=1 group by site_id";
        $ret = $db->createCommand($sql)->queryAll();
        $data = array();
        foreach ($ret as $r) {
            $data[$r['site_id']] = $r['count'];
        }
        $total = 0;
        foreach ($site as $s) {
            $count = isset($data[$s['site_id']]) ? $data[$s['site_id']] : 0;
            $total += $count;
            $lastRow[] = $count;
        }
        $lastRow[] = $total;
        fputcsv($fp, $lastRow);

        rewind($fp);
        Yii::app()->request->sendFile('daily_'.$date.'.csv', stream_get_contents($fp));

    }

    public function actionRange($start='20130601', $end='20130607') {
        $db = Yii::app()->db;

        $site = $db->createCommand("select site_id,site_py from tbl_site")->queryAll();

        $fp = fopen('php://temp', 'w');
        $firstRow = array('');
        foreach ($site as $s) {
            $firstRow[] = $s['site_py'];
        }
        $firstRow[] = 'subtotal';
        fputcsv($fp, $firstRow);

        for ($date=intval($start); $date<=intval($end); $date++) {
			if ($date>20131231 && $date<20140101)
				continue;
				
            $sql = "select count(*) as count,site_id from tbl_short_video_meta where source=1 and pub_date=".intval($date)." group by site_id";
            $ret = $db->createCommand($sql)->queryAll();
            $data = array();
            foreach ($ret as $r) {
                $data[$r['site_id']] = $r['count'];
            }
            $row = array($date);
            $subTotal = 0;
            foreach ($site as $s) {
                $count = isset($data[$s['site_id']]) ? $data[$s['site_id']] : 0;
                $subTotal += $count;
                $row[] = $count;
            }
            $row[] = $subTotal;
            fputcsv($fp, $row);
        }

        $lastRow = array('total');
        $sql = "select count(*) as count,site_id from tbl_short_video_meta where source=1 group by site_id";
        $ret = $db->createCommand($sql)->queryAll();
        $data = array();
        foreach ($ret as $r) {
            $data[$r['site_id']] = $r['count'];
        }
        $total = 0;
        foreach ($site as $s) {
            $count = isset($data[$s['site_id']]) ? $data[$s['site_id']] : 0;
            $total += $count;
            $lastRow[] = $count;
        }
        $lastRow[] = $total;
        fputcsv($fp, $lastRow);

        rewind($fp);
        Yii::app()->request->sendFile('range_'.$start.'_'.$end.'.csv', stream_get_contents($fp));

    }

    public function actionEmail($to, $site='iqiyi', $date='', $from='zhengwenxiao@baidu.com') {
        if (!$to) {
            echo "Please enter email addresses.";
            exit;
        }
        if (!$date) {
            $date = date('Ymd', time());
        }

        $db = Yii::app()->db;

        $site_id = $db->createCommand("select site_id from tbl_site where site_py='{$site}'")->queryScalar();
        if ($site_id) {
            $start = strtotime($date);
            $end = $start + 24*3600;
            $condition = "source=1 and add_time>{$start} and add_time<{$end} and site_id={$site_id}";
        }
        else {
            echo "Please enter valid site name.";
            exit;
        }

        $channel = $db->createCommand("select channel_id,title_chs from tbl_channel")->queryAll();
        $sql = "select count(*) as count,site_id,channel_id from tbl_short_video_meta where $condition group by channel_id";
        $ret = $db->createCommand($sql)->queryAll();

        $data = array();
        foreach ($ret as $r) {
            $data[$r['channel_id']] = $r['count'];
        }
        $total = 0;
        $message = "{$date} {$site} 提交视频统计\n\n";
        foreach ($channel as $c) {
            $name = $c['title_chs'];
            $count = isset($data[$c['channel_id']]) ? $data[$c['channel_id']] : 0;
            $message .= "$name\t$count\n";
            $total += $count;
        }
        $message .= "总计\t$total\n\n";

        $bad_data = $db->createCommand("select video_title,source_detail_link,simg_url from tbl_short_video_meta where flag=9 and $condition")->queryAll();
        if ($bad_data) {
            $message .= "共" . count($bad_data) . "条视频不符合要求：\n\n";
            foreach ($bad_data as $v) {
                $message .= "标题：" . $v['video_title'] . "\n" ;
                $message .= "链接：" . $v['source_detail_link'] . "\n" ;
                $message .= "图片：" . $v['simg_url'] . "\n\n" ;
            }
        }

        $subject = "{$date} {$site} 提交视频统计";
        $headers = "MIME-Version: 1.0\r\n" .
            "Content-type: text/plain; charset=utf-8\r\n" .
            "From: $from\r\n" .
            "Reply-To: $from\r\n" .
            "Cc: $from\r\n";


        echo "主题：$subject<br/>";
        echo "收件人：$to<br/>";
        echo "正文：".str_replace("\n","<br/>",$message)."<br/>";
        if (mail($to, "=?UTF-8?B?".base64_encode($subject)."?=", $message, $headers)) {
            echo "发送状态：成功";
        }
        else {
            echo "发送状态：失败";
        }
    }

    public function actionTest() {
        $data = array('hello', '你好');
//        print_r($data);

        $f = fopen("/home/video/webroot/short-video-data/output/test.csv", "w");
        fputcsv($f, $data, ',', '"');
        fclose($f);

        $fp = fopen("php://temp", "w");
        fputcsv($fp, $data, ',', '"');
        rewind($fp);
        Yii::app()->request->sendFile('test.csv', stream_get_contents($fp));
    }
}
