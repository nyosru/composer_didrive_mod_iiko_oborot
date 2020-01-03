<?php

/**
  определение функций для TWIG
 */
//creatSecret
//echo __FILE__.' '.__LINE__;

$function = new Twig_SimpleFunction('iiko_oborot__get_oborots_on_sp', function ( $db, string $sp, string $date_start, string $date_finish ) {

//    \Nyos\mod\items::$sql_itemsdop_add_where_array = array(
//        ':sp' => $sp
//    );
//    \Nyos\mod\items::$sql_itemsdop2_add_where = '
//        INNER JOIN `mitems-dops` md1 
//            ON 
//                md1.id_item = mi.id 
//                AND md1.name = \'sale_point\'
//                AND md1.value = :sp
//        ';    
//    $oborots = \Nyos\mod\items::getItemsSimple($db, 'sale_point_oborot');
//    \Nyos\mod\items::$sql_itemsdop_add_where_array = array(
//        ':sp' => $sp
//    );
//    \Nyos\mod\items::$sql_itemsdop2_add_where = '
//        INNER JOIN `mitems-dops` md1 
//            ON 
//                md1.id_item = mi.id 
//                AND md1.name = \'sale_point\'
//                AND md1.value = :sp
//        ';

    \Nyos\mod\items::$join_where = ' INNER JOIN `mitems-dops` mid '
            . ' ON mid.id_item = mi.id '
            . ' AND mid.name = \'date\' '
            . ' AND mid.value_date >= :ds '
            . ' AND mid.value_date <= :df '
            . ' INNER JOIN `mitems-dops` mid2 '
            . ' ON mid2.id_item = mi.id '
            . ' AND mid2.name = \'sale_point\' '
            . ' AND mid2.value = :sp '

    ;
    \Nyos\mod\items::$var_ar_for_1sql[':sp'] = $sp;
    \Nyos\mod\items::$var_ar_for_1sql[':ds'] = $date_start;
    \Nyos\mod\items::$var_ar_for_1sql[':df'] = $date_finish;
    $oborots = \Nyos\mod\items::get($db, 'sale_point_oborot');

//    echo '<pre>';
//    \f\pa($oborots,2);
//    echo '</pre>';

    $re = ['summa' => 0];

    foreach ($oborots as $k => $v) {
//        if (
//                isset($v['sale_point']) &&
//                $v['sale_point'] == $sp &&
//                isset($v['date']) &&
//                $v['date'] >= $date_start &&
//                $v['date'] <= $date_finish
//        ) {

        $re[$v['date']] = $v;
        //$re[$v['date']]['id'] = $v['id'];

        $re['summa'] += $v['oborot_server'];
//        }
    }

//    foreach ($oborots['data'] as $k => $v) {
//        if (isset($v['dop'])) {
//            
//        }
//    }

    return $re;
});
$twig->addFunction($function);

//$function = new Twig_SimpleFunction('iiko_oborot__get_oborots_on_sp', function ( $db, string $sp, string $date_start, string $date_finish ) {
//$function = new Twig_SimpleFunction('iiko_oborot__get_month_oborot_on_sp', function ( $db, string $sp, string $date_start, string $date_finish ) {
//
//    return [];
//});
//$twig->addFunction($function);



$function = new Twig_SimpleFunction('show__oborots_raschet', function ( $db, array $get ) {

    // \f\pa($get);
// https://adomik.dev.uralweb.info/vendor/didrive_mod/iiko_checks/ajax.php?date=2019-12-04&action=get_list654&show=info&sp_key_iiko=efac5394-ef56-4c43-adeb-6a849e0024d4

    $g = $get;
    $g['hide_form_to_html'] = 'da';
    $g['hide_form'] = 'da';

    $g['action'] = 'get_list654';
    $g['show'] = 'info';

    $g['ajax_calculate'] = 'da';

    //$g['show_ar'] = 'da';
    // $g['show_sql'] = 'info';

    $w = file_get_contents('http://' . $_SERVER['HTTP_HOST'] . '/vendor/didrive_mod/iiko_checks/ajax.php?' . http_build_query($g));

    return $w;
});
$twig->addFunction($function);
