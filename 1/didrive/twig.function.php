<?php

/**
  определение функций для TWIG
 */

$function = new Twig_SimpleFunction('iiko_oborot__get_oborots_on_sp', function ( $db, string $sp, string $date_start, string $date_finish ) {

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
    
    // \Nyos\mod\items::$where2dop = ' AND ( name ';
    
    $oborots = \Nyos\mod\items::get($db, 'sale_point_oborot');

    $re = ['summa' => 0];

    foreach ($oborots as $k => $v) {
        $re[$v['date']] = $v;
        $re['summa'] += !empty($v['oborot_hand']) ? $v['oborot_hand'] : $v['oborot_server'];
    }

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
