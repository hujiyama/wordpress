<?php


// rizm@heteml.jp に変更中  8/24
// 
// fieldのセットアップ
// catoms-admin@cyberagent.co.jp

// セット内容

// if(in_category('awsacountpassrequest')){
//   $headers = array( 'Content-Type: text/html; charset=UTF-8' );
// $messagetest =<<<EOS
// ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━<br>
// ※このメールには返信しないでください！<br>
// ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━<br><br>

// 作業依頼内容URL<br>
// [編集画面]　{$adminurl}<br>
// IDとPASSが必要です。<br><br>

// ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━<br>
// {$statusdiscription}<br>{$quickpostcom}
// ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━<br><br>
// ここまで共通↑
// [社員番号]　{$post_stuff}<br>
// [依頼期日]　{$date}<br>
// [連絡先(電話番号)]　{$tel}<br>
// [Cc]　{$ccaddress}<br><br><br>


// [プロジェクト名]　{$project}<br>
// [費用負担部門]　{$department}<br>
// [担当者一覧]　{$staffs}<br><br>

// ここから共通↓
// 添付：<img src="{$file}" alt="" /><br>
// [備考]　{$memo}<br>



// EOS;
// wp_mail( $mail_address, $subject, $messagetest,$headers);
// }
// else{


// }

// function my_pre_save_post( $post_id )
// {
//     // check if this is to be a new post
//     if( $post_id != 'new_post' )
//     {
//         return $post_id;
//     }

//     // Create a new post
//   $post = array(
// 'post_status' => 'publish' ,
// 'post_title' => $_POST['fields']['field_name'] ,
// 'post_type' => 'posts' ,
// );

//     // insert the post
//     $post_id = wp_insert_post( $post ); 

//     // update $_POST['return']
//     $_POST['return'] = add_query_arg( array('post_id' => $post_id), $_POST['return'] );    

//     // return the new ID
//     return $post_id;
// }

// add_filter('pre_save_post' , 'my_pre_save_post' );




// ステータスの対応中の時に送信不可
function updated_send_email( $post_id ) {



  // ----------------------------------------------------------送信タイミング!!!!
  // If this is just a revision, don't send the email.
   if ( wp_is_post_revision( $post_id ))
    return;




     if ( wp_is_post_revision( $post_id ))
    return;

   // if ( wp_is_edit_post( $post_id ));

   // if ( wp_set_post_terms( $post_id ));

   else if ( wp_publish_post( $post_id ));
  


  // ここからget field
// ----------------------------------------------------------フィールド開始
$postid = get_the_ID();
$adminurl = get_edit_post_link( $id, $context );



$modified = get_field('corresp'); //対応者名表示
            if (isset($modified)){
              $modified = get_field('corresp');
              $addfronts  = $modified;
              $addfront = explode("@", $addfronts);
              $adminaddress = $addfront[0]; // 分解表示 アカウント出力
            } 
//コメント本文
$textecomment = get_field('textediter_comment');
 if($textecomment) :
//メールへ添付
 $contenttemp = '<br>コメント：'.$textecomment.'';
// コメント欄へUP
 $contentcomment = $textecomment;

$data = array(
    'comment_post_ID' => $postid,
    'comment_author' => $modified,
    'comment_content' => $contentcomment,
    'comment_type' => 'Comment', //trackback他
    'comment_parent' => 0,// 親コメントかどうか 0か id を設定
    'user_id' => 1,
);
wp_insert_comment($data);
endif;

//　必要であれば追加 //
// 'comment_author_email' => $adminaddress,
//認証街の方への処理    'comment_approved' => 1,





// -------------------------------------------------------------デフォルト--data!!!


// 社員番号
$post_stuff = get_field('staffnumber');
// 依頼期日　　dateはタイトルとの連結でエラーが出るので直接タイトルを入れてある
$date = DateTime::createFromFormat('Y/m/d', get_field('due-date'));

   $date = get_field('due-date');
   // extract Y,M,D
   $y = substr($date, 0, 4);
   $m = substr($date, 4, 2);
   $d = substr($date, 6, 2);

// 連絡先(電話番号)
$tel = get_field('tel');
// メール宛先（cc）はsbujectの上に設置
if($tel) :
$tels = '連絡先(電話番号)'.$tel.'<br>';
endif;


// 本文フィールド
//-------------------------------------------------------------AWSアカウント発行申請
// プロジェクト名 
$project = get_field('projectname');

// 費用負担部門 
$department = get_field('department');

// 担当者一覧 
$staffs = get_field('staffs');


//-------------------------------------------------------------IPアドレス払出・返却申請

// 操作
$field = get_field_object('ip_action');
$value = get_field('ip_action');
$ip_action = $field['choices'][ $value ];

// IP種別
$field = get_field_object('ip_kind');
$value = get_field('ip_kind');
$ip_kind = $field['choices'][ $value ];

// 領域 
$field = get_field_object('area');
$value = get_field('area');
$area = $field['choices'][ $value ];

// IPv4 or IPv6 
$field = get_field_object('vfour_or_vsix');
$value = get_field('vfour_or_vsix');
$vfour_or_vsix = $field['choices'][ $value ];

// VLAN  
$vlan = get_field('vlan');

// 個数
$ip_number = get_field('ip_number');

// プロジェクト名 
$projectname = get_field('projectname');

// 用途
$use = get_field('use');

// FQDN
$fqdn = get_field('fqdn');

// 返却IP
$return_ip = get_field('return_ip');

// セカンドメール
$secondmail = get_field('secondmail');


//-------------------------------------------------------------Cloverアカウント発行申請

// プロジェクト名 
$projectname = get_field('projectname');

//アカウント名
$accountname = get_field('accountname');

//unit1
$unit_one = get_field('unit_one');

//unit2
$unit_two = get_field('unit_two');

//サービス
$service = get_field('service');

// リストチェック
// $field = get_field_object('list_chk');
// $value = get_field('list_chk');
// $list_chk = $field['choices'][ $value ];

//-------------------------------------------------------------FireWall設定申請

//依頼区分
$field = get_field_object('divide');
$value = get_field('divide');
$divide = $field['choices'][ $value ];

//設定区分
$field = get_field_object('set_up_kind');
$value = get_field('set_up_kind');
$set_up_kind = $field['choices'][ $value ];


//CloverAccount
$cloveraccount = get_field('cloveraccount');

//Src to Dest
$field = get_field_object('src_to_dest');
$value = get_field('src_to_dest');
$src_to_dest = $field['choices'][ $value ];


//Source IPv4
$src_ipv_four = get_field('src_ipv_four');

//Source IPv4
$src_ipv_four_bulk = get_field('src_ipv_four_bulk');

//Source IPv6
$src_ipv_six = get_field('src_ipv_six');

//Source IPv6
$src_ipv_six_bulk = get_field('src_ipv_six_bulk');

//Dest IPv4
$dest_ipv_four = get_field('dest_ipv_four');

//Dest IPv4
$dest_ipv_four_bulk = get_field('dest_ipv_four_bulk');

//Dest IPv6
$dest_ipv_six = get_field('dest_ipv_six');

//Dest IPv6
$dest_ipv_six_bulk = get_field('dest_ipv_six_bulk');

// DestProtocol
$field = get_field_object('destprotocol');
$value = get_field('destprotocol');
$destprotocol = $field['choices'][ $value ];


// DestPort
$destport = get_field('destport');

//削除内容
$delete = get_field('delete');

//作業事前連絡
$field = get_field_object('advance_notice');
$value = get_field('advance_notice');
$advance_notice = $field['choices'][ $value ];



//-------------------------------------------------------------GCPアカウント発行申請

// プロジェクト名 
$projectname = get_field('projectname');

//費用負担部署
$costs_borne = get_field('costs_borne');

// 担当者一覧 
$staffs = get_field('staffs');



//-------------------------------------------------------------NAT設定変更申請

// Source IP Address
$source_ip_address = get_field('source_ip_address');

// FQDN
$fqdn = get_field('fqdn');

// 領域
$territory = get_field('territory');


//作業事前連絡
$field = get_field_object('advance_notice');
$value = get_field('advance_notice');
$advance_notice = $field['choices'][ $value ];



//-------------------------------------------------------------SSL証明書申請


//費用負担部署
$costs_borne = get_field('costs_borne');

 //作業区分選択(新規,更新,削除)
 $field = get_field_object('work_description');
 $value = get_field('work_description');
 $work_description = $field['choices'][ $value ];


//証明書bit数
$field = get_field_object('certificate_bit');
$value = get_field('certificate_bit');
$certificate_bit = $field['choices'][ $value ];

//EV証明書
$field = get_field_object('certificate_ev');
$value = get_field('certificate_ev');
$certificate_ev = $field['choices'][ $value ];


//確認用URL
$confirmation_url = get_field('confirmation_url');

//ドメイン名
$domain_name = get_field('domain_name');


//証明書をインストールする機器(IP)
$machinery_ip = get_field('machinery_ip');

//LB内パーティション
$partition = get_field('partition');

// 署名アルゴリズム
$field = get_field_object('algorithm');
$value = get_field('algorithm');
$algorithm = $field['choices'][ $value ];

//証明書期限更新連絡先
$telnumber = get_field('telnumber');

//作業事前連絡
$field = get_field_object('advance_notice');
$value = get_field('advance_notice');
$advance_notice = $field['choices'][ $value ];


//-------------------------------------------------------------VPNアカウント発行・解除申請

//発行 or 解除
$field = get_field_object('issure_or_remove');
$value = get_field('issure_or_remove');
$issure_or_remove = $field['choices'][ $value ];

// VPN廃止対象者
$vpn_name = get_field('vpn_name');


// VPN廃止対象者メールアドレス
$vpn_address = get_field('vpn_address');


// VPN廃止対象者所属
$vpn_project = get_field('vpn_project');

//VPN廃止日
$vpn_day = get_field('vpn_day');



//-------------------------------------------------------------その他依頼

//作業詳細
$work_detail = get_field('work_detail');



//-------------------------------------------------------------受取依頼

//納品数
$delivery_number = get_field('delivery_number');

//納品予定日
$delivery_date = get_field('delivery_date');


//搬入対象機器
$machinery = get_field('machinery');


//搬入車両情報
$vehicle = get_field('vehicle');


//搬入機器の開梱有無
$field = get_field_object('open');
$value = get_field('open');
$open = $field['choices'][ $value ];


//搬入機器の対処
$cope = get_field('cope');


//搬入業者連絡先
$contact = get_field('contact');

//搬入時連絡の方法
$how_to = get_field('how_to');

//作業事前連絡
$field = get_field_object('advance_notice');
$value = get_field('advance_notice');
$advance_notice = $field['choices'][ $value ];




//-------------------------------------------------------------各種DC申請代行依頼

//日時
$date_and_time = get_field('date_and_time');

//作業内容
$work = get_field('work');

//入館者
$visitors = get_field('visitors');

//入館者ふりがな
$visitors_furigana = get_field('visitors_furigana');


//連絡先
$tel = get_field('tel');



//-------------------------------------------------------------各種お問い合わせ
//依頼種別
$field = get_field_object('kind');
$value = get_field('kind');
$kind = $field['choices'][ $value ];
if($kind) :
$kinds = '依頼種別：'.$kind.'<br>';
endif;

//利用端末
$field = get_field_object('vpn_device');
$value = get_field('vpn_device');
$vpn_device = $field['choices'][ $value ];
if($vpn_device) :
$vpn_devices = '利用端末：'.$vpn_device.'<br>';
endif;

//バージョン
$vpn_version = get_field('vpn_version');
if($vpn_version) :
$vpn_versions = 'バージョン：'.$vpn_version.'<br>';
endif;

//設定手順において完了している項番
$field = get_field_object('vpn_number_mac');
$value = get_field('vpn_number_mac');
$vpn_number_mac = $field['choices'][ $value ];
if($vpn_number_mac) :
$vpn_number_macs = '設定手順において完了している項番/mac：'.$vpn_number_mac.'<br>';
endif;

//設定手順において完了している項番
$field = get_field_object('vpn_number_windows');
$value = get_field('vpn_number_windows');
$vpn_number_windows = $field['choices'][ $value ];
if($vpn_number_windows) :
$vpn_number_windowss = '設定手順において完了している項番/windows：'.$vpn_number_windows.'<br>';
endif;

//症状
$vpn_insident = get_field('vpn_insident');
if($vpn_insident) :
$vpn_insidents = '症状：'.$vpn_insident.'<br>';
endif;
//問い合わせ内容
$other_insident = get_field('other_insident');
if($other_insident) :
$other_insidents = '問い合わせ内容：'.$other_insident.'<br>';
endif;

//-------------------------------------------------------------新ドメイン取得申請

//ドメイン名
$domain_name = get_field('domain_name');

//NS管理
$field = get_field_object('ns_management');
$value = get_field('ns_management');
$ns_management = $field['choices'][ $value ];


//DNSzoneオーナー
$dns_zones_owner = get_field('dns_zones_owner');

//サービス名
$servicename = get_field('servicename');


//設定希望NS
$name_server = get_field('name_server');


//費用負担部署
$costs_borne = get_field('costs_borne');


//-------------------------------------------------------------構内配線調整／回線工事の立会依頼

//実施日時
$date_the_time = get_field('date_the_time');


//業者名
$trader_name = get_field('trader_name');


//業者連絡先
$suppliers_contact = get_field('suppliers_contact');

//作業詳細
$working_details = get_field('working_details');

//作業事前連絡
$field = get_field_object('advance_notice');
$value = get_field('advance_notice');
$advance_notice = $field['choices'][ $value ];


//-------------------------------------------------------------機器状況確認依頼

//機器状況確認依頼
$rack = get_field('rack');

//対象機器Unit
$unit = get_field('unit');

//対象機器シリアル
$serial = get_field('serial');

//対象機器ホスト名
$host_name = get_field('host_name');


//現在の状況
$present_situation = get_field('present_situation');


//作業事前連絡
$field = get_field_object('advance_notice');
$value = get_field('advance_notice');
$advance_notice = $field['choices'][ $value ];


//実施希望期間
$implementation_period = get_field('implementation_period');


//-------------------------------------------------------------物理サーバ貸出・返却・保守依頼

//作業区分
$field = get_field_object('work_classification');
$value = get_field('work_classification');
$work_classification = $field['choices'][ $value ];

//タイプ
$field = get_field_object('type');
$value = get_field('type');
$type = $field['choices'][ $value ];


//台数
$number = get_field('number');

//Cloverオーナー
$cloverowner = get_field('cloverowner');


//ホスト名
$hostname = get_field('hostname');

//VLAN
$vlan = get_field('vlan');

//Tftp Profile
$tftp_profile = get_field('tftp_profile');


//症状
$insedent = get_field('insedent');


//-------------------------------------------------------------資産外機器の受取依頼

//搬入対象機器
$target_device = get_field('target_device');

//納品数
$delivery_number = get_field('delivery_number');

//搬入車両情報
$vehicle_information = get_field('vehicle_information');


//搬入機器の開梱有無
$field = get_field_object('unpacking');
$value = get_field('unpacking');
$unpacking = $field['choices'][ $value ];


//搬入機器の対処
$deal = get_field('deal');

//搬入業者連絡先
$contact_number = get_field('contact_number');

//搬入時連絡の方法
$contact_way = get_field('contact_way');


//作業前連絡の有無
$field = get_field_object('advance_notice');
$value = get_field('advance_notice');
$advance_notice = $field['choices'][ $value ];



//-------------------------------------------------------------障害対応依頼

//対象機器ラック
$subject_rack = get_field('subject_rack');

//対象機器Unit
$unit = get_field('unit');

//対象機器シリアル
$serial = get_field('serial');

//対象機器ホスト名
$host_name = get_field('host_name');


//障害状況
$present_situation = get_field('trouble_status');


//監視状況
$watching = get_field('watching');


//電源断許可
$power = get_field('power');



//保守対応実施有無
$field = get_field_object('maintain');
$value = get_field('maintain');
$maintain = $field['choices'][ $value ];


//作業前連絡
$field = get_field_object('advance_notice');
$value = get_field('advance_notice');
$advance_notice = $field['choices'][ $value ];


//-------------------------------------------------------------電源ON/OFF依頼


//対象機器ラック
$subject_rack = get_field('subject_rack');

//対象機器Unit
$unit = get_field('unit');

//対象機器シリアル
$serial = get_field('serial');

//対象機器ホスト名
$host_name = get_field('host_name');

//電源ON・OFF
$field = get_field_object('power');
$value = get_field('power');
$power = $field['choices'][ $value ];

//作業前連絡
$field = get_field_object('advance_notice');
$value = get_field('advance_notice');
$advance_notice = $field['choices'][ $value ];


//-------------------------------------------------------------共通フッター



// 添付
$file = get_field('file');


//$file =  str_replace("http:","",$fileimg);

// 備考
$memo = get_field('memo');


//
// SUBJECT  ステータス　カテゴリー　トップ状況　＋　メッセージ
//
// ----------------------------------------------------------SUBJECT + ディスクリプション

// カテゴリー名取得 申請書タイトル
$cat = get_the_category(); $cat = $cat[0];
$category =$cat->cat_name;

// ステータス名取得　ステータス状況
$term = array_pop(get_the_terms($post->ID, 'status'));
$term_p = $term->parent;
if ( ! $term_p == 0 ){
$term = array_shift(get_the_terms($post->ID, 'status'));
}
$categorystatus_set = esc_html($term->term_id);
$term_set = 78;
if($term_set == $categorystatus_set){
   $categorystatus = '新規'; //下書き状態の場合
}else{
$categorystatus = esc_html($term->name);
}



// ステータス（カテゴリ）からの説明文（メッセージ）ディスクリプション
$statusdiscriptions = $term->description;
$statusdiscription = nl2br($statusdiscriptions);

// 現状使用していないが今後使用かも？
$posttags = get_the_tags();
if ($posttags) {
foreach($posttags as $tag) {
$option_tag_tag = $tag->name . ' '; 
}
}
// subject
$subject = "依頼ID：{$postid}"."_{$category}"."/{$categorystatus}";

// デフォルトメッセージフォームメッセージフォームから追加メッセージとあったが
// 結局コメントボックスだけでやることになったのでコメントアウト
//    $defaultmess = get_field('defaultmess');


// //コンテンツ本文取得
// global $post;
// $contents = mb_substr($post->post_content, 0, 30);
// $contenttemp = nl2br($contents);



// コメント取得
// The Query
$comments_query_args = array( 'post_type' => 'post','number' => 1,'status' => 'approve', 'post_id' => $post_id,);
$comments_query = new WP_Comment_Query();     
$recent_comments = $comments_query->query($comments_query_args);

// Comment Loop
if ($recent_comments ) {
  foreach ( $recent_comments as $recent_comment ) {
   $quickpostcom = $recent_comment->comment_content;
  }
}



// 申請者メールアドレス取得   
$post = get_post($post_id);
if ($post){
$author = get_userdata($post->post_author);
$user_email = $author->user_email;
}

// mailaddress
$ccaddress= post_custom('e_mail_cc');


// 本番用メルアド
// catoms-admin@cyberagent.co.jp
// ----------------------------------------------------------ヘッダーへ収納
$mail_address = array('fujiyamaseiji@gmail.com', $ccaddress, $user_email);


// カテゴリー判定
$category = get_the_category();
$cat_id   = $category[0]->cat_ID;
$cat_name = $category[0]->cat_name;
$cat_slug = $category[0]->category_nicename;

//
  //----------------------------------------------------------AWSアカウント発行申請 message
//

if(in_category('awsacountpassrequest')){
  $headers = array( 'Content-Type: text/html; charset=UTF-8' );
$messagetest =<<<EOS
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━<br>
※このメールには返信しないでください！<br>
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━<br><br>

作業依頼内容URL<br>
[編集画面]　{$adminurl}<br>
IDとPASSが必要です。<br><br>

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━<br>
{$statusdiscription}<br>

{$quickpostcom}
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━<br><br>

[社員番号]　{$post_stuff}<br>
[依頼期日]　{$date}<br>
[連絡先(電話番号)]　{$tel}<br>
[Cc]　{$ccaddress}<br><br><br>


[プロジェクト名]　{$project}<br>
[費用負担部門]　{$department}<br>
[担当者一覧]　{$staffs}<br><br>

添付：<img src="{$file}" alt="" /><br>
[備考]　{$memo}<br>



EOS;
wp_mail( $mail_address, $subject, $messagetest,$headers);
}
else{


}
//
  //----------------------------------------------------------IPアドレス払出・返却申請 message
//

if(in_category('ip_paying_return')){
  $headers = array( 'Content-Type: text/html; charset=UTF-8' );
$ip_paying_return =<<<EOS
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━<br>
※このメールには返信しないでください！<br>
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━<br><br>

作業依頼内容URL<br>
[編集画面]　{$adminurl}<br>
IDとPASSが必要です。<br><br>

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━<br>
{$statusdiscription}<br>{$quickpostcom}
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━<br><br>

社員番号：{$post_stuff}<br>
依頼期日：{$date}<br>
連絡先(電話番号)：{$tel}<br>
Cc：{$ccaddress}<br><br>

操作：{$ip_action}<br>
IP種別：{$ip_kind}<br>
領域：{$area}<br>
IPv4 or IPv6：{$vfour_or_vsix}<br>
VLAN：{$vlan}<br>
個数：{$ip_number}<br>
プロジェクト名：{$projectname}<br>
用途：{$use}<br>
FQDN：{$fqdn}<br>
返却IP：{$return_ip}<br>
セカンドメール：{$secondmail}<br><br>

添付：<img src="{$file}" alt="" /><br>
備考：{$memo}


EOS;
wp_mail( $mail_address, $subject, $ip_paying_return,$headers);
}
else{

}


//
  //----------------------------------------------------------Cloverアカウント発行申請 message
//

  if(in_category('cloveracountpassrequest')){
    $headers = array( 'Content-Type: text/html; charset=UTF-8' );
$cloveracountpassrequest =<<<EOS
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━<br>
※このメールには返信しないでください！<br>
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━<br><br>

作業依頼内容URL<br>
[編集画面]　{$adminurl}<br>
IDとPASSが必要です。<br><br>

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━<br>
{$statusdiscription}<br>{$quickpostcom}
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━<br><br>

社員番号：{$post_stuff}<br>
依頼期日：{$date}<br>
連絡先(電話番号)：{$tel}<br>
Cc：{$ccaddress}<br><br>

プロジェクト名：{$projectname}<br>
アカウント名:：{$accountname}<br>
unit1：{$unit_one}<br>
unit2：{$unit_two}<br><br>

サービス：{$service}<br>
リストチェック：{$list_chk}<br><br>

添付：<img src="{$file}" alt="" /><br>
備考：{$memo}


EOS;
wp_mail( $mail_address, $subject, $cloveracountpassrequest,$headers);
}
else{

}

//
  //----------------------------------------------------------FireWall設定申請 message
//

  if(in_category('firewallconfiguration_application')){
    $headers = array( 'Content-Type: text/html; charset=UTF-8' );
$firewallconfiguration_application =<<<EOS
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━<br>
※このメールには返信しないでください！<br>
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━<br><br>

作業依頼内容URL<br>
[編集画面]　{$adminurl}<br>
IDとPASSが必要です。<br><br>

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━<br>
{$statusdiscription}<br>{$quickpostcom}
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━<br><br>

社員番号：{$post_stuff}<br>
依頼期日：{$date}<br>
連絡先(電話番号)：{$tel}<br>
Cc：{$ccaddress}<br><br>

依頼区分：{$divide}<br>
設定区分：{$set_up_kind}<br>
CloverAccount：{$cloveraccount}<br>
Src to Dest：{$src_to_dest}<br>
Source IPv4：{$src_ipv_four}<br>
Source IPv4：{$src_ipv_four_bulk}<br>
Source IPv6：{$src_ipv_six}<br>
Source IPv6：{$src_ipv_six_bulk}<br>
Dest IPv4：{$dest_ipv_four}<br>
Dest IPv4：{$dest_ipv_four_bulk}<br>
Dest IPv6：{$dest_ipv_six}<br>
Dest IPv6：{$dest_ipv_six_bulk}<br>
DestProtocol：{$destprotocol}<br>
DestPort：{$destport}<br>
削除内容：{$delete}<br>
作業事前連絡：{$advance_notice}<br><br>

添付：<img src="{$file}" alt="" /><br>
備考：{$memo}


EOS;
wp_mail( $mail_address, $subject, $firewallconfiguration_application,$headers);
}
else{

}


//
  //----------------------------------------------------------GCPアカウント発行申請 message
//

  if(in_category('gcpaccountissuance_application')){
    $headers = array( 'Content-Type: text/html; charset=UTF-8' );
$gcpaccountissuance_application =<<<EOS
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━<br>
※このメールには返信しないでください！<br>
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━<br><br>

作業依頼内容URL<br>
[編集画面]　{$adminurl}<br>
IDとPASSが必要です。<br><br>

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━<br>
{$statusdiscription}<br>{$quickpostcom}
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━<br><br>

社員番号：{$post_stuff}<br>
依頼期日：{$date}<br>
連絡先(電話番号)：{$tel}<br>
Cc：{$ccaddress}<br><br>

プロジェクト名 ：{$projectname}<br>
費用負担部署：{$costs_borne}<br>
担当者一覧 ：{$staffs}<br><br>

添付：<img src="{$file}" alt="" /><br>
備考：{$memo}


EOS;
wp_mail( $mail_address, $subject, $gcpaccountissuance_application,$headers);
}
else{

}


//
  //----------------------------------------------------------NAT設定変更申請 message
//

  if(in_category('nat_configuration_change_request')){
    $headers = array( 'Content-Type: text/html; charset=UTF-8' );
$nat_configuration_change_request =<<<EOS
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━<br>
※このメールには返信しないでください！<br>
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━<br><br>

作業依頼内容URL<br>
[編集画面]　{$adminurl}<br>
IDとPASSが必要です。<br><br>

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━<br>
{$statusdiscription}<br>{$quickpostcom}
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━<br><br>

社員番号：{$post_stuff}<br>
依頼期日：{$date}<br>
連絡先(電話番号)：{$tel}<br>
Cc：{$ccaddress}<br><br>

Source IP Address：{$source_ip_address}<br>
FQDN：{$fqdn}<br>
領域：{$territory}<br>
作業事前連絡；{$advance_notice}<br><br>

添付：<img src="{$file}" alt="" /><br>
備考：{$memo}


EOS;
wp_mail( $mail_address, $subject, $nat_configuration_change_request,$headers);
}
else{

}

//
  //----------------------------------------------------------SSL証明書申請 message
//

  if(in_category('sslcertificate_application')){
    $headers = array( 'Content-Type: text/html; charset=UTF-8' );
$sslcertificate_application =<<<EOS
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━<br>
※このメールには返信しないでください！<br>
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━<br><br>

作業依頼内容URL<br>
[編集画面]　{$adminurl}<br>
IDとPASSが必要です。<br><br>

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━<br>
{$statusdiscription}<br>{$quickpostcom}
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━<br><br>

社員番号：{$post_stuff}<br>
依頼期日：{$date}<br>
連絡先(電話番号)：{$tel}<br>
Cc：{$ccaddress}<br><br>

費用負担部署：{$costs_borne}<br>
作業区分選択(新規,更新,削除)；{$work_description}<br>
証明書bit数：{$certificate_bit}<br>
リストチェック：{$certificate_ev}<br>
確認用URL：{$confirmation_url}<br>
ドメイン名：{$domain_name}<br>
証明書をインストールする機器(IP)：{$machinery_ip}<br>
LB内パーティション：{$partition}<br>
署名アルゴリズム：{$algorithm}<br>
証明書期限更新連絡先：{$telnumber}<br>
作業事前連絡：{$advance_notice}<br><br>

添付：<img src="{$file}" alt="" /><br>
備考：{$memo}


EOS;
wp_mail( $mail_address, $subject, $sslcertificate_application,$headers);
}
else{

}



$commenttest =  get_comment_text( $comment_ID, $args );

//
  //----------------------------------------------------------VPNアカウント発行・解除申請 message
//

  if(in_category('vpn_account_iandc_application')){
    $headers = array( 'Content-Type: text/html; charset=UTF-8' );
$vpn_account_iandc_application =<<<EOS
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━<br>
※このメールには返信しないでください！<br>
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━<br><br>

作業依頼内容URL<br>
[編集画面]　{$adminurl}<br>
IDとPASSが必要です。<br><br>

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━<br>
{$statusdiscription}<br>{$quickpostcom}
追加コメントエディタから
{$contenttemp}
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━<br><br>

社員番号：{$post_stuff}<br>
依頼期日：{$date}<br>
連絡先(電話番号)：{$tel}<br>
Cc：{$ccaddress}<br><br>

テストメール用<br>

{$commenttest}



添付：<img src="{$file}" alt="" /><br>
備考：{$memo}


EOS;
wp_mail( $mail_address, $subject, $vpn_account_iandc_application,$headers);
}
else{

}

//
  //----------------------------------------------------------その他依頼 message
//

  if(in_category('other_request')){

$headers = array( 'Content-Type: text/html; charset=UTF-8' );

$other_request =<<< EOM
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━<br>
※このメールには返信しないでください！<br>
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━<br><br>

作業依頼内容URL<br>
[編集画面]　{$adminurl}<br>
IDとPASSが必要です。<br><br>

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━<br>
{$statusdiscription}<br>{$quickpostcom}
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━<br><br>

社員番号：{$post_stuff}<br>
依頼期日：{$date}<br>
連絡先(電話番号)：{$tel}<br>
Cc：{$ccaddress}<br><br>

作業詳細：{$work_detail}<br><br><br>



添付：<img src="{$file}" alt="" /><br>
備考：{$memo}<br>


EOM;
wp_mail( $mail_address, $subject, $other_request,$headers);
}
else{

}



//
  //----------------------------------------------------------受取依頼 message
//

  if(in_category('receipt_request')){
    $headers = array( 'Content-Type: text/html; charset=UTF-8' );
$receipt_request =<<<EOS
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━<br>
※このメールには返信しないでください！<br>
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━<br><br>

作業依頼内容URL<br>
[編集画面]　{$adminurl}<br>
IDとPASSが必要です。<br><br>

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━<br>
{$statusdiscription}<br>{$quickpostcom}
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━<br><br>

社員番号：{$post_stuff}<br>
依頼期日：{$date}<br>
連絡先(電話番号)：{$tel}<br>
Cc：{$ccaddress}<br><br>

納品数：{$delivery_number}<br>
納品予定日：{$delivery_date}<br>
搬入対象機器：{$machinery}<br>
搬入車両情報：{$vehicle}<br>
搬入機器の開梱有無：{$open}<br>
搬入機器の対処：{$cope}<br>
搬入業者連絡先：{$contact}<br>
搬入時連絡の方法：{$how_to}<br>
作業事前連絡：{$advance_notice}<br><br>

添付：<img src="{$file}" alt="" /><br>
備考：{$memo}


EOS;
wp_mail( $mail_address, $subject, $receipt_request,$headers);
}
else{

}


//
  //----------------------------------------------------------各種DC申請代行依頼 message
//

  if(in_category('various_dc_aprequest')){
    $headers = array( 'Content-Type: text/html; charset=UTF-8' );
$various_dc_aprequest =<<<EOS
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━<br>
※このメールには返信しないでください！<br>
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━<br><br>

作業依頼内容URL<br>
[編集画面]　{$adminurl}<br>
IDとPASSが必要です。<br><br>

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━<br>
{$statusdiscription}<br>{$quickpostcom}
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━<br><br>

社員番号：{$post_stuff}<br>
依頼期日：{$date}<br>
{$tels}
Cc：{$ccaddress}<br><br>

日時：{$date_and_time}<br>
作業内容：{$work}<br>
入館者：{$visitors}<br>
入館者ふりがな：{$visitors_furigana}<br>
連絡先：{$tel}<br><br>

添付：<img src="{$file}" alt="" /><br>
備考：{$memo}


EOS;
wp_mail( $mail_address, $subject, $various_dc_aprequest,$headers);
}
else{

}

//
  //----------------------------------------------------------各種お問い合わせ message
//

  if(in_category('contactbox')){
    $headers = array( 'Content-Type: text/html; charset=UTF-8' );
$contactbox =<<<EOS
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━<br>
※このメールには返信しないでください！<br>
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━<br><br>

作業依頼内容URL<br>
[編集画面]　{$adminurl}<br>
IDとPASSが必要です。<br><br>

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━<br>
{$statusdiscription}<br>{$quickpostcom}
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━<br><br>

社員番号：{$post_stuff}<br>
依頼期日：{$date}<br>
連絡先(電話番号)：{$tel}<br>
Cc：{$ccaddress}<br><br>

{$kinds}
{$vpn_devices}
{$vpn_versions}
{$vpn_number_macs}
{$vpn_number_windowss}
{$vpn_insidents}
{$other_insidents}

添付：<img src="{$file}" alt="" /><br>
備考：{$memo}<br>


{$contenttemp}

EOS;
wp_mail( $mail_address, $subject, $contactbox,$headers);
}
else{

}


//
  //----------------------------------------------------------新ドメイン取得申請 message
//

  if(in_category('new_domaina_application')){
    $headers = array( 'Content-Type: text/html; charset=UTF-8' );
$new_domaina_application =<<<EOS
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━<br>
※このメールには返信しないでください！<br>
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━<br><br>

作業依頼内容URL<br>
[編集画面]　{$adminurl}<br>
IDとPASSが必要です。<br><br>

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━<br>
{$statusdiscription}<br>{$quickpostcom}
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━<br><br>

社員番号：{$post_stuff}<br>
依頼期日：{$date}<br>
連絡先(電話番号)：{$tel}<br>
Cc：{$ccaddress}<br><br>

ドメイン名：{$domain_name}<br>
NS管理：{$ns_management}<br>
DNSzoneオーナー：{$dns_zones_owner}<br>
サービス名：{$servicename}<br>
設定希望NS:{$name_server}<br>
費用負担部署：{$costs_borne}<br><br>

添付：<img src="{$file}" alt="" /><br>
備考：{$memo}


EOS;
wp_mail( $mail_address, $subject, $new_domaina_application,$headers);
}
else{

}



//
  //----------------------------------------------------------構内配線調整／回線工事の立会依頼 message
//

  if(in_category('wrofpwadjustment_line_construction')){
    $headers = array( 'Content-Type: text/html; charset=UTF-8' );
$wrofpwadjustment_line_construction =<<<EOS
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━<br>
※このメールには返信しないでください！<br>
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━<br><br>

作業依頼内容URL<br>
[編集画面]　{$adminurl}<br>
IDとPASSが必要です。<br><br>

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━<br>
{$statusdiscription}<br>{$quickpostcom}
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━<br><br>

社員番号：{$post_stuff}<br>
依頼期日：{$date}<br>
連絡先(電話番号)：{$tel}<br>
Cc：{$ccaddress}<br><br>

実施日時：{$date_the_time}<br>
業者名:{$trader_name}<br>
業者連絡先：{$suppliers_contact}<br>
作業詳細：{$working_details}<br>
作業事前連絡：{$advance_notice}<br><br>

添付：<img src="{$file}" alt="" /><br>
備考：{$memo}


EOS;
wp_mail( $mail_address, $subject, $wrofpwadjustment_line_construction,$headers);
}
else{

}



//
  //----------------------------------------------------------機器状況確認依頼 message
//

  if(in_category('device_status_c_request')){
    $headers = array( 'Content-Type: text/html; charset=UTF-8' );
$device_status_c_request =<<<EOS
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━<br>
※このメールには返信しないでください！<br>
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━<br><br>

作業依頼内容URL<br>
[編集画面]　{$adminurl}<br>
IDとPASSが必要です。<br><br>

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━<br>
{$statusdiscription}<br>{$quickpostcom}
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━<br><br>

社員番号：{$post_stuff}<br>
依頼期日：{$date}<br>
連絡先(電話番号)：{$tel}<br>
Cc：{$ccaddress}<br><br>

対象機器ラック：{$rack}<br>
対象機器Unit：{$unit}<br>
対象機器シリアル：{$serial}<br>
対象機器ホスト名：{$host_name}<br>
現在の状況：{$present_situation}<br>
作業事前連絡：{$advance_notice}<br>
実施希望期間：{$implementation_period}<br><br>

添付：<img src="{$file}" alt="" /><br>
備考：{$memo}


EOS;
wp_mail( $mail_address, $subject, $device_status_c_request,$headers);
}
else{

}

//
  //----------------------------------------------------------物理サーバ貸出・返却・保守依頼 message
//

  if(in_category('pscandmaintenance_request')){
    $headers = array( 'Content-Type: text/html; charset=UTF-8' );
$pscandmaintenance_request =<<<EOS
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━<br>
※このメールには返信しないでください！<br>
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━<br><br>

作業依頼内容URL<br>
[編集画面]　{$adminurl}<br>
IDとPASSが必要です。<br><br>

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━<br>
{$statusdiscription}<br>{$quickpostcom}
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━<br><br>

社員番号：{$post_stuff}<br>
依頼期日：{$date}<br>
連絡先(電話番号)：{$tel}<br>
Cc：{$ccaddress}<br><br>

作業区分：{$work_classification}<br>
タイプ：{$type}<br>
台数：{$number}<br>
Cloverオーナー：{$cloverowner}<br>
ホスト名：{$hostname}<br>
VLAN：{$vlan}<br>
Tftp Profile：{$tftp_profile}<br>
症状：{$insedent}<br><br>

添付：<img src="{$file}" alt="" /><br>
備考：{$memo}


EOS;
wp_mail( $mail_address, $subject, $pscandmaintenance_request,$headers);
}
else{

}

//
  //----------------------------------------------------------資産外機器の受取依頼 message
//

  if(in_category('r_r_of_assetso_equipment')){
    $headers = array( 'Content-Type: text/html; charset=UTF-8' );
$r_r_of_assetso_equipment =<<<EOS
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━<br>
※このメールには返信しないでください！<br>
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━<br><br>

作業依頼内容URL<br>
[編集画面]　{$adminurl}<br>
IDとPASSが必要です。<br><br>

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━<br>
{$statusdiscription}<br>{$quickpostcom}
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━<br><br>

社員番号：{$post_stuff}<br>
依頼期日：{$date}<br>
連絡先(電話番号)：{$tel}<br>
Cc：{$ccaddress}<br><br>

搬入対象機器：{$target_device}<br>
納品数：{$delivery_number}<br>
搬入車両情報：{$vehicle_information}<br>
搬入機器の開梱有無：{$unpacking}<br>
搬入機器の対処：{$deal}<br>
搬入業者連絡先：{$contact_number}<br>
搬入時連絡の方法：{$contact_way}<br>
作業前連絡の有無：{$advance_notice}<br><br>

添付：<img src="{$file}" alt="" /><br>
備考：{$memo}


EOS;
wp_mail( $mail_address, $subject, $r_r_of_assetso_equipment,$headers);
}
else{

}



//
  //----------------------------------------------------------障害対応依頼 message
//

  if(in_category('failure_corresponding_request')){
    $headers = array( 'Content-Type: text/html; charset=UTF-8' );
$failure_corresponding_request =<<<EOS
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━<br>
※このメールには返信しないでください！<br>
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━<br><br>

作業依頼内容URL<br>
[編集画面]　{$adminurl}<br>
IDとPASSが必要です。<br><br>

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━<br>
{$statusdiscription}<br>{$quickpostcom}
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━<br><br>

社員番号：{$post_stuff}<br>
依頼期日：{$date}<br>
連絡先(電話番号)：{$tel}<br>
Cc：{$ccaddress}<br><br>

対象機器ラック：{$subject_rack}<br>
対象機器Unit：{$unit}<br>
対象機器シリアル：{$serial}<br>
対象機器ホスト名：{$host_name}<br>
障害状況：{$present_situation}<br>
監視状況：{$watching}<br>
電源断許可：{$power}<br>
保守対応実施有無：{$maintain}<br>
作業前連絡：{$advance_notice}<br><br>

添付：<img src="{$file}" alt="" /><br>
備考：{$memo}


EOS;
wp_mail( $mail_address, $subject, $failure_corresponding_request,$headers);
}
else{

}

//
  //----------------------------------------------------------電源ON/OFF依頼 message
//

  if(in_category('poweronoffrequest')){
    $headers = array( 'Content-Type: text/html; charset=UTF-8' );
$poweronoffrequest =<<<EOS
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━<br>
※このメールには返信しないでください！<br>
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━<br><br>

作業依頼内容URL<br>
[編集画面]　{$adminurl}<br>
IDとPASSが必要です。<br><br>

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━<br>
{$statusdiscription}<br>{$quickpostcom}
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━<br><br>

社員番号：{$post_stuff}<br>
依頼期日：{$date}<br>
連絡先(電話番号)：{$tel}<br>
Cc：{$ccaddress}<br><br>

対象機器ラック：{$subject_rack}<br>
対象機器Unit：{$unit}<br>
対象機器シリアル：{$serial}<br>
対象機器ホスト名：{$host_name}<br>
電源ON・OFF：{$power}<br>
作業前連絡：{$advance_notice}<br><br>

添付：<img src="{$file}" alt="" /><br>
備考：{$memo}


EOS;
wp_mail( $mail_address, $subject, $poweronoffrequest,$headers);
}
else{

}





// function updated_send_email( $post_id ) { の wrap
}


// 通常はsavetだが２重送信エラー回避の為、acf/save_post使用
add_filter( 'acf/save_post', 'updated_send_email', 10, 1 );


// ２重送信が起きる場合は下記のコメントを外す　処理を一度でキル
 //remove_action('acf/save_post', 'updated_send_email');

// メッセージフォームに文字がある場合も保存前に差し込む
 //add_filter( 'acf/wp_insert_post', 'updated_send_email', 10, 2 );
// add_action('acf/publish_post','updated_send_email', 150);



?>