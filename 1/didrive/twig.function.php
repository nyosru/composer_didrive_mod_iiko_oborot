<?php

/**
  определение функций для TWIG
 */
//creatSecret

//echo __FILE__.' '.__LINE__;

$function = new Twig_SimpleFunction('iiko_oborot__get_oborots_on_sp', function ( $db, string $sp, string $date_start, string $date_finish ) {

    

    \Nyos\mod\items::$sql_itemsdop_add_where_array = array(
        ':sp' => $sp
    );
    \Nyos\mod\items::$sql_itemsdop2_add_where = '
        INNER JOIN `mitems-dops` md1 
            ON 
                md1.id_item = mi.id 
                AND md1.name = \'sale_point\'
                AND md1.value = :sp
        ';    
    $oborots = \Nyos\mod\items::getItemsSimple($db, 'sale_point_oborot');
    // \f\pa($oborots);
    
    $re = [];
    
    foreach( $oborots['data'] as $k => $v ){
        $re[$v['dop']['date']] = $v['dop'];
        $re[$v['dop']['date']]['id'] = $v['id'];
    }

    return $re;
});
$twig->addFunction($function);
