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
    $oborots = \Nyos\mod\items::getItemsSimple($db, 'sale_point_oborot');
//    echo '<pre>';
//    \f\pa($oborots,2);
//    echo '</pre>';
    
    $re = [ 'summa' => 0 ];

    foreach ($oborots['data'] as $k => $v) {
        if ( 
                isset($v['dop']['sale_point']) && 
                $v['dop']['sale_point'] == $sp &&
                isset($v['dop']['date']) && 
                $v['dop']['date'] >= $date_start && 
                $v['dop']['date'] <= $date_finish                
                ) {

            $re[$v['dop']['date']] = $v['dop'];
            $re[$v['dop']['date']]['id'] = $v['id'];
            
            $re['summa'] += $v['dop']['oborot_server'];
            
        }
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
    
    $w = file_get_contents('http://'.$_SERVER['HTTP_HOST'].'/vendor/didrive_mod/iiko_checks/ajax.php?'.http_build_query($g));
    
    return $w;
});
$twig->addFunction($function);
