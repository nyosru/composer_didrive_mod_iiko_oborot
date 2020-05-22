<?php

/**
  определение функций для TWIG
 */
$function = new Twig_SimpleFunction('iiko_oborot__get_oborots_on_sp', function ( $db, string $sp, string $date_start, string $date_finish ) {

    // старая версия
    if (1 == 2) {

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

        // echo '<br/>#'.__LINE__;
        // \Nyos\mod\items::$show_sql = true;
        $oborots = \Nyos\mod\items::get($db, 'sale_point_oborot');
    }

//    \Nyos\mod\items::$cancel_cash = true;
//    \Nyos\mod\items::$show_sql = true;
//    \Nyos\mod\items::$timer_show = true;

//    \Nyos\mod\items::$var_ar_for_1sql2 = [];

    \Nyos\mod\items::$search['sale_point'] = $sp;
    \Nyos\mod\items::$between_date['date'] = [$date_start, $date_finish];

    // $oborots = \Nyos\mod\items::get($db, 'sale_point_oborot');

    //\Nyos\mod\items::$show_sql = true;
    $oborots = \Nyos\mod\items::get($db, \Nyos\mod\JobDesc::$mod_oborots);
    // \f\pa($oborots, '', '', '$oborots');

    $re = ['summa' => 0];

    foreach ($oborots as $k => $v) {
        if (isset($v['date'])) {
            $re[$v['date']] = $v;
            if (isset($v['oborot_hand']) && $v['oborot_hand'] > 0) {
                $re['summa'] += $v['oborot_hand'];
            } elseif (isset($v['oborot_server']) && $v['oborot_server'] > 0) {
                $re['summa'] += $v['oborot_server'];
            }
        }
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
