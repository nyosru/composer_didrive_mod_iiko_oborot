<?php

/**
  класс модуля
 * */

namespace Nyos\mod;

if (!defined('IN_NYOS_PROJECT'))
    throw new \Exception('Сработала защита от розовых хакеров, обратитесь к администрратору');

/**
 * класс для работы с данными по обороту
 */
class IikoOborot {

//foreach (\Nyos\Nyos::$menu as $k => $v) {
//    if ($v['type'] == 'iiko_checks' && $v['version'] == 1) {
//
//        \Nyos\api\Iiko::$db_type = $v['db_type'];
//        \Nyos\api\Iiko::$db_host = $v['db_host'];
//        \Nyos\api\Iiko::$db_port = $v['db_port'];
//        \Nyos\api\Iiko::$db_base = $v['db_base'];
//        \Nyos\api\Iiko::$db_login = $v['db_login'];
//        \Nyos\api\Iiko::$db_pass = $v['db_pass'];
//
//        break;
//    }
//}


    public static $db_type = '';
    public static $db_host = '';
    public static $db_port = '';
    public static $db_base = '';
    public static $db_login = '';
    public static $db_pass = '';
    
    public static $db_connect = null;
    
    public static $show_html = false;

    public static function connectDb() {
        
        $dops = array(
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
//                \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8'
        );

        $db7 = new \PDO(
                self::$db_type
                . ':dbname=' . ( self::$db_base ?? '' )
                . ';host=' . ( self::$db_host ?? '' )
                . ( isset(self::$db_port{1}) ? ';port=' . self::$db_port : '' )
                , ( isset(self::$db_login{1}) ? self::$db_login : '')
                , ( isset(self::$db_pass{1}) ? self::$db_pass : '')
                , $dops
        );

        self::$db_connect = $db7;

    }

    public static function loadOborotFromServer($sp_key, $date) {

        if( empty(self::$db_connect) )
        self::connectDb();
        
        $sql = 'SELECT '
                . ' dbo.OrderPaymentEvent.date '
                . ' , '
                . ' dbo.OrderPaymentEvent.prechequeTime , '
                . ' dbo.OrderPaymentEvent.orderSum, '
                . ' dbo.OrderPaymentEvent.sumCard, '
                . ' dbo.OrderPaymentEvent.sumCash , '
                . ' dbo.OrderPaymentEvent.unmodifiable , '
                . ' dbo.OrderPaymentEvent.changeSum , '
                . ' dbo.OrderPaymentEvent.receiptsSum , '
                . ' dbo.OrderPaymentEvent.problemOpName '
                . ' , '
                . ' dbo.OrderPaymentEvent.orderNum '
                . ' , '
                . ' dbo.OrderPaymentEvent.revision '
                . ' , '
                . ' dbo.OrderPaymentEvent.department '
                . ' , '
                . ' dbo.OrderPaymentEvent.auth_card_slip '
                . ' , '
                . ' dbo.OrderPaymentEvent.auth_user '
                . ' , '
                . ' dbo.OrderPaymentEvent.closeTime '
//            . ' , '
//            . ' dbo.OrderPaymentEvent.discount '
//            . ' , '
//            . ' dbo.OrderPaymentEvent.increase '
                . ' , '
                . ' dbo.OrderPaymentEvent.isBanquet '
                . ' , '
                . ' dbo.OrderPaymentEvent.numGuests '
                . ' , '
                . ' dbo.OrderPaymentEvent.openTime '
                . ' , '
                . ' dbo.OrderPaymentEvent.[order] '
                . ' , '
                . ' dbo.OrderPaymentEvent.orderNum '
                . ' , '
                . ' dbo.OrderPaymentEvent.orderSum '
                . ' , '
                . ' dbo.OrderPaymentEvent.orderSumAfterDiscount '
//            . ' , '
//            . ' dbo.OrderPaymentEvent.prechequeTime '
                . ' , '
                . ' dbo.OrderPaymentEvent.priceCategory '
                . ' , '
                . ' dbo.OrderPaymentEvent.problemOpName '
                . ' , '
                . ' dbo.OrderPaymentEvent.problemPriority '
                . ' , '
                . ' dbo.OrderPaymentEvent.problemType '
                . ' , '
                . ' dbo.OrderPaymentEvent.receiptsSum '
                . ' , '
                . ' dbo.OrderPaymentEvent.restaurantSection '
                . ' , '
                . ' dbo.OrderPaymentEvent.session_group '
                . ' , '
                . ' dbo.OrderPaymentEvent.session_id '
                . ' , '
                . ' dbo.OrderPaymentEvent.session_number '
                . ' , '
                . ' dbo.OrderPaymentEvent.storned '
                . ' , '
                . ' dbo.OrderPaymentEvent.tableNum '
                . ' , '
                . ' dbo.OrderPaymentEvent.[user] '
                . ' , '
                . ' dbo.OrderPaymentEvent.waiter '
                . ' , '
                . ' dbo.OrderPaymentEvent.cashRegister '
                . ' , '
                . ' dbo.OrderPaymentEvent.cashier '
                . ' , '
                . ' dbo.OrderPaymentEvent.changeSum '
                . ' , '
                . ' dbo.OrderPaymentEvent.counteragent '
                . ' , '
                . ' dbo.OrderPaymentEvent.detailedCheque '
                . ' , '
                . ' dbo.OrderPaymentEvent.divisions '
                . ' , '
                . ' dbo.OrderPaymentEvent.fiscalChequeNumber '
                . ' , '
                . ' dbo.OrderPaymentEvent.isDelivery '
                . ' , '
                . ' dbo.OrderPaymentEvent.nonCashPaymentType '
                . ' , '
                . ' dbo.OrderPaymentEvent.orderType '
                . ' , '
                . ' dbo.OrderPaymentEvent.pcCard '
                . ' , '
                . ' dbo.OrderPaymentEvent.pcDiscountCard '
                . ' , '
                . ' dbo.OrderPaymentEvent.pcUser '
                . ' , '
                . ' dbo.OrderPaymentEvent.sumCard '
                . ' , '
                . ' dbo.OrderPaymentEvent.sumCash '
                . ' , '
                . ' dbo.OrderPaymentEvent.sumCredit '
                . ' , '
                . ' dbo.OrderPaymentEvent.sumPlanned '
                . ' , '
                . ' dbo.OrderPaymentEvent.sumPrepay '
                . ' , '
                . ' dbo.OrderPaymentEvent.unmodifiable '
                . ' , '
                . ' dbo.OrderPaymentEvent.writeoffPaymentType '
                . ' , '
                . ' dbo.OrderPaymentEvent.writeoffRatio '
                . ' , '
                . ' dbo.OrderPaymentEvent.writeoffReason '
                . ' , '
                . ' dbo.OrderPaymentEvent.writeoffUser '
                . ' , '
                . ' dbo.OrderPaymentEvent.orderDeleted '
                . ' , '
                . ' dbo.OrderPaymentEvent.originName '
                . ' , '
                . ' dbo.OrderPaymentEvent.vatInvoiceId '
                . '
                FROM 
                    dbo.OrderPaymentEvent
                WHERE 
                '
                .
                ' restaurantSection = \'' . $sp_key . '\' '
                . ' AND date = \'' . date('Y-m-d', strtotime($date)) . '\' '
                . ' ORDER BY prechequeTime ASC '
        ;

        if ( self::$show_html === true )
            echo '<pre>' . $sql . '</pre>';

        $ff = self::$db_connect->prepare($sql);

        $ff->execute();

        if ( self::$show_html === true )
            echo '<table cellpadding=10 border=1 >'; // <tr><td>1</td><td>2</td></tr>';

        $sum = 0;

        $n = 1;

        while ($e = $ff->fetch()) {

            if ( self::$show_html === true ) {
                if ($n == 1) {
                    echo '<tr>';

                    foreach ($e as $k => $v) {
                        echo '<td>' . $k . '</td>';
                    }

                    echo '</tr>';
                }
            }
            $n++;

//if( $e['orderSum'] == 543 ){
            if ($n == 2) {
                $ar2 = $e;
            }

            if ( self::$show_html === true )
                echo '<tr>';

            foreach ($e as $k => $v) {

                if ( self::$show_html === true )
                    echo '<td>' . iconv('windows-1251', 'utf-8', $v) . '</td>';

                if ($k == 'sumCard' || $k == 'sumCash')
                    $sum += $v;
            }

            if ( self::$show_html === true )
                echo '</tr>';

//$e['user'] = mb_convert_encoding($e['user'],'UTF-8','auto');
//$e['user'] = utf8_decode($e['user']);
//                $e['user'] = utf8_encode($e['user']);
//                echo '<br/>'.mb_detect_order($e['user']);
//$e['user'] = iconv('UCS-2LE','UTF-8',substr(base64_decode($e['user']),0,-1));
//$e['user'] = html_entity_decode($e['user'], ENT_COMPAT | ENT_HTML401, 'UTF-8');
//                $e3[] = $e;
        }
//    \f\pa($e3);

        if ( self::$show_html === true ) {
            echo '</table>';

            echo '<p>Сумма ' . $sum . '</p>';

            \f\pa($ar2, 2, '', '$ar2');
        }

        $sql = 'SELECT '
//            . '  id '
//            . ' , '
//            . ' lastModifyNode '
//            . ' , '
                . ' date '
                . ' , '
                . ' num '
                . ' , '
                . ' sum '
                . ' , '
                . ' type '
//            . ' , '
//            . ' revision '
//            . ' , '
//            
//            . ' department '
//            
//            . ' , '
//            . ' cashFlowCategory '
//            . ' , '
//            . ' cashOrderNumber '
//            . ' , '
//            . ' comment '
////            . ' , '
////            . ' conception '
//            . ' , '
//            . ' created '
//            . ' , '
//            . ' userCreated '
//            . ' , '
//            . ' documentId '
//            . ' , '
//            . ' documentItemId '
//            . ' , '
//            . ' from_amount '
//            . ' , '
//            . ' from_account '
//            . ' , '
//            . ' from_counteragent '
//            . ' , '
//            . ' from_product '
//            . ' , '
//            . ' modified '
//            . ' , '
//            . ' userModified '
//            . ' , '
////            . ' session_id '
////            . ' , '
//            . ' to_amount '
//            . ' , '
//            . ' to_account '
//            . ' , '
//            . ' to_counteragent '
//            . ' , '
//            . ' to_product '
//            . ' , '
//            . ' auth_card_slip '
//            . ' , '
//            . ' auth_user '
//            . ' , '
//            . ' cashier '
//            . ' , '
//            . ' causeEvent_id '
//            . ' , '
//            . ' penaltyOrBonusType '
//            . ' , '
//            . ' chequeNumber '
//            . ' , '
//            . ' isFiscal '
//            . ' , '
//            . ' orderId '
//            . ' , '
//            . ' paymentType '
//            . ' , '
//            . ' approvalCode '
//            . ' , '
//            . ' cardNumber '
//            . ' , '
//            . ' cardOwnerCompany '
//            . ' , '
//            . ' cardTypeName '
//            . ' , '
//            . ' nominal '
//            . ' , '
//            . ' vouchersNum '
//            . ' , '
//            . ' salesSum '
//            . ' , '
//            . ' program '
//            . ' , '
//            . ' revenueLevel '
//            . ' , '
//            . ' ndsPercent '
//            . ' , '
//            . ' sumNds '
//            . ' , '
//            . ' attendanceEntry_id '
//            . ' , '
//            . ' scheduleItem_id '
//            . ' , '
//            . ' writeoff_id '
//            . ' , '
//            . ' inventoryEvent_id '
//            . ' , '
//            . ' originDepartment '
//            . ' , '
//            . ' currency '
//            . ' , '
//            . ' currencyRate '
//            . ' , '
//            . ' sumInCurrency '
//            . ' , '
//            . ' to_productSize '
                . '
                FROM 
                    dbo.AccountingTransaction
                WHERE '
                . ' date = \'' . $ar2['date'] . '\' '
                . ' AND num = \'' . $ar2['session_number'] . '\' '

//            . ' AND sum < 0 '
//            . ' AND ( type = \'CARD\' OR type = \'CASH\' ) '
//            . 'created > \'' . date('Y-m-d 08:00:00', strtotime($date)) . '\'
//                AND created < \'' . date('Y-m-d 05:00:00', strtotime($date)+3600*24 ) . '\' '
// .' AND created < \'' . date('Y-m-d 12:00:00', strtotime($date) + 3600 * 24) . '\' '
        ;

        if ( self::$show_html === true )
            echo '<pre>' . $sql . '</pre>';

        $ff = self::$db_connect->prepare($sql);
        $ff->execute();

        if ( self::$show_html === true )
            echo '<table cellpadding=10 border=1 >'; // <tr><td>1</td><td>2</td></tr>';

        $sum2 = 0;

        $n = 1;

        while ($e = $ff->fetch()) {

            if ( self::$show_html === true ) {
                if ($n == 1) {
                    echo '<tr>';

                    foreach ($e as $k => $v) {
                        echo '<td>' . $k . '</td>';
                    }

                    echo '</tr>';
                }
            }
            $n++;

            if ( self::$show_html === true ) {

                echo '<tr>';

                foreach ($e as $k => $v) {

// 1173
                    if (isset($v{2})) {
                        echo '<td>';
//                    echo '<nobr>' ;
//                    foreach($ar2 as $kk => $vv ){
//                        if( $vv != 0 &&  $vv == $v ){
//                            echo $kk.' ['.$vv.']++<br/>';
//                        }
//                    }
//                    echo '</nobr>' ;
//                    echo '<br/><br/>' ;
//echo '//'.iconv('windows-1251', 'utf-8', $v) . '//</td>';
                        echo iconv('windows-1251', 'utf-8', $v) . '</td>';
                    } else {
                        echo '<td>';
//                    echo '<nobr>' ;
//                    foreach($ar2 as $kk => $vv ){
//                        if( $vv != 0 && $vv == $v ){
//                            echo $kk.' ['.$vv.']++<br/>';
//                        }
//                    }
//                    echo '</nobr>' ;
//                    echo '<br/><br/>' ;
//echo '//'.$v . '//</td>';
                        echo $v . '</td>';
                    }
                }
                echo '</tr>';
            }

            if ($e['sum'] < 0) {

                if (isset($e['type']) && ( trim($e['type']) == 'CASH' || trim($e['type']) == 'CARD' )) {

                    if ( self::$show_html === true )
                        \f\pa($e);

                    $sum2 += $e['sum'];
                }

            }

        }

        if ( self::$show_html === true )
            echo '</table>';

        if( $sum == 0 ){
            throw new \Exception('не получилось получить сумму оборота за сутки');
        }
        
        return \f\end3('получили данные по обороту точки', true, array(
            'oborot' => (int) ( $sum + $sum2 ),
            'plus' => (int) $sum,
            'minus' => (int) $sum2
        ));

    }

}
