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

    public static $cash = [];
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

        if (empty(self::$db_base) || empty(self::$db_host) || empty(self::$db_login) || empty(self::$db_pass)) {

            foreach (\Nyos\Nyos::$menu as $k => $v) {
                if ($v['type'] == 'iiko_checks' && $v['version'] == 1) {

                    \Nyos\mod\IikoOborot::$db_type = $v['db_type'];
                    \Nyos\api\Iiko::$db_type = $v['db_type'];
                    \Nyos\mod\IikoOborot::$db_host = $v['db_host'];
                    \Nyos\api\Iiko::$db_host = $v['db_host'];
                    \Nyos\mod\IikoOborot::$db_port = $v['db_port'];
                    \Nyos\api\Iiko::$db_port = $v['db_port'];
                    \Nyos\mod\IikoOborot::$db_base = $v['db_base'];
                    \Nyos\api\Iiko::$db_base = $v['db_base'];
                    \Nyos\mod\IikoOborot::$db_login = $v['db_login'];
                    \Nyos\api\Iiko::$db_login = $v['db_login'];
                    \Nyos\mod\IikoOborot::$db_pass = $v['db_pass'];
                    \Nyos\api\Iiko::$db_pass = $v['db_pass'];

                    break;
                }
            }
        }

        $dops = array(
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
//                \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8'
//            \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8mb4' COLLATE 'utf8mb4_unicode_ci' "
        );

        $db7 = new \PDO(
                self::$db_type
                . ':dbname=' . ( self::$db_base ?? '' )
                . ';host=' . ( self::$db_host ?? '' )
                . ( isset(self::$db_port{1}) ? ';port=' . self::$db_port : '' )
                // . ';charset=utf8mb4'
                // . ';charset=windows-1251'
                , ( isset(self::$db_login{1}) ? self::$db_login : '')
                , ( isset(self::$db_pass{1}) ? self::$db_pass : '')
                , $dops
        );

        self::$db_connect = $db7;
    }

    /**
     * сканируем дату и точку продаж по id
     * @param type $db
     * @param type $date
     * @param type $key
     */
    public static function scanServerOborot($db, $db7, string $date, string $key) {

        if (1 == 1) {

            $date = date('Y-m-d', strtotime($_REQUEST['date']));

            $sql = // 'set @date1=\''.date('Y-m-d 00:00:00', strtotime('2019-07-11') ).'\' '.
//        '
//        declare @TIME1 as datetime
//        declare @TIME2 as datetime
//        SET @TIME1 = \''.date('Y-m-d 00:00:00', strtotime('2019-07-11') ).'\'
//        SET @TIME2 = \''.date('Y-m-d 23:59:00', strtotime('2019-07-11') ).'\'
//        '
                    // . 'set @date2=\''.date('d.m.Y 23:00:00', strtotime('2019-07-11') ).'\' '
//            . 
                    'SELECT '
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







                    // . ' dbo.OrderPaymentEvent.orderDeleted , ' 
                    // . ' dbo.OrderPaymentEvent.session_number , ' 
                    // . ' dbo.OrderPaymentEvent.restaurantSection , ' 
                    // . ' dbo.OrderPaymentEvent.tableNum ' 
                    // ' dbo.EmployeeAttendanceEntry.employee \'user\', '.
//            . ' dbo.EmployeeAttendanceEntry.personalSessionStart \'start\',
//                    dbo.EmployeeAttendanceEntry.personalSessionEnd \'end\'
//                    '
                    . '
                FROM 
                    dbo.OrderPaymentEvent
                WHERE 
                '
                    .
                    ' restaurantSection = \'' . addslashes($key) . '\' '
                    // . ' AND date > \'' . date('Y-m-d 00:00:00', strtotime($date)) . '\' '
                    . ' AND date = \'' . date('Y-m-d', strtotime($date)) . '\' '
//            . '
//                    AND prechequeTime > \'' . date('Y-m-d 05:00:00', strtotime($date)) . '\'
//                    AND prechequeTime < \'' . date('Y-m-d 05:00:00', strtotime($date) + 3600 * 24) . '\'
//                    '
//            .'
//                    AND prechequeTime > @TIME1  
//                    '
//            .'
//                    AND prechequeTime between @TIME1 and @TIME2 
//                    '
//            .'
//                    AND prechequeTime >= @TIME1
//                    '
//            .'
//                    AND prechequeTime <= :date2 
//                    '
                    // . (!empty($date_fin) ? ' AND personalSessionStart <= :date_end ' : '' )
                    // .' LIMIT 0,10 '
                    . ' ORDER BY prechequeTime ASC '
            ;

            if (isset($_REQUEST['show_sql']))
                echo '<pre>' . $sql . '</pre>';

            $ff = $db7->prepare($sql);

//    $ar_in_sql = array(
//        // ':id_user' => 'f34d6d84-5ecb-4a40-9b03-71d03cb730cb',
//        // ':id_user' => $id_user_iiko,
//        ':date1' => date('d.m.Y 00:00:00', strtotime('2019-07-11') ),
//        ':date2' => date('d.m.Y 23:59:00', strtotime('2019-07-11') ),
//        //':date3' => date('d.m.Y', strtotime('2019-07-11') ),
//        //':sp_iiko_id' => 'f939f35f-c169-4be9-9933-5af230748ede'
//    );
//\f\pa($ar_in_sql);
            //$ff->execute($ar_in_sql);
            $ff->execute();
            //$e3 = $ff->fetchAll();
//            $e3 = [];

            if (isset($_REQUEST['show']))
                echo '<table cellpadding=10 border=1 >'; // <tr><td>1</td><td>2</td></tr>';


            $sum = 0;

            $n = 1;

            while ($e = $ff->fetch()) {

                if (isset($_REQUEST['show'])) {
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

                if (isset($_REQUEST['show']))
                    echo '<tr>';

                foreach ($e as $k => $v) {

                    if (isset($_REQUEST['show'])) {
                        echo '<td>';
                        if (!empty($v)) {
                            echo @iconv('windows-1251', 'utf-8', $v);
                        }
                        echo '</td>';
                    }

                    if ($k == 'sumCard' || $k == 'sumCash')
                        $sum += $v;
                }

                if (isset($_REQUEST['show']))
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

            if (isset($_REQUEST['show'])) {
                echo '</table>';

                echo '<p>Сумма ' . $sum . '</p>';

                if (isset($_REQUEST['show_ar']))
                    \f\pa($ar2, 2, '', '$ar2');
            }

            if (empty($ar2))
                return ['data' => ['oborot' => 'x']];

            // \f\pa($ar2);

            $sql = // 'set @date1=\''.date('Y-m-d 00:00:00', strtotime('2019-07-11') ).'\' '.
//        '
//        declare @TIME1 as datetime
//        declare @TIME2 as datetime
//        SET @TIME1 = \''.date('Y-m-d 00:00:00', strtotime('2019-07-11') ).'\'
//        SET @TIME2 = \''.date('Y-m-d 23:59:00', strtotime('2019-07-11') ).'\'
//        '
                    // . 'set @date2=\''.date('d.m.Y 23:00:00', strtotime('2019-07-11') ).'\' '
//            . 
                    'SELECT '
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

            if (isset($_REQUEST['show_sql']))
                echo '<pre>' . $sql . '</pre>';

            $ff = $db7->prepare($sql);

//    $ar_in_sql = array(
//        // ':id_user' => 'f34d6d84-5ecb-4a40-9b03-71d03cb730cb',
//        // ':id_user' => $id_user_iiko,
//        ':date1' => date('d.m.Y 00:00:00', strtotime('2019-07-11') ),
//        ':date2' => date('d.m.Y 23:59:00', strtotime('2019-07-11') ),
//        //':date3' => date('d.m.Y', strtotime('2019-07-11') ),
//        //':sp_iiko_id' => 'f939f35f-c169-4be9-9933-5af230748ede'
//    );
//\f\pa($ar_in_sql);
            //$ff->execute($ar_in_sql);
            $ff->execute();
            //$e3 = $ff->fetchAll();
//            $e3 = [];

            if (isset($_REQUEST['show']))
                echo '<table cellpadding=10 border=1 >'; // <tr><td>1</td><td>2</td></tr>';


            $sum2 = 0;

            $n = 1;

            while ($e = $ff->fetch()) {

                if (isset($_REQUEST['show'])) {
                    if ($n == 1) {
                        echo '<tr>';

                        foreach ($e as $k => $v) {
                            echo '<td>' . $k . '</td>';
                        }

                        echo '</tr>';
                    }
                }
                $n++;

                // if ($e['sum'] == 1173 || 1 == 1 ) {
                //if ($e['num'] == 866 ) {
                // if ($e['num'] == $ar2['session_number'] ) {
                if (1 == 1) {

                    if (isset($_REQUEST['show'])) {

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
                }

                if ($e['sum'] < 0) {

                    //if ( isset( $e['sum'] )  && $e['sum'] < 0 && isset( $e['type'] )  && ( $e['type'] == 'CASH' || $e['type'] == 'CARD' ) )
                    if (isset($e['type']) && ( trim($e['type']) == 'CASH' || trim($e['type']) == 'CARD' )) {

//                        if (isset($_REQUEST['show']))
//                            \f\pa($e);

                        if (isset($_REQUEST['ajax_calculate']))
                            echo '<br/>minus ' . $e['sum'] . ' руб ' . $e['date'] . ' ' . $e['type'];

                        $sum2 += $e['sum'];
                    }
                }

                //$e['user'] = mb_convert_encoding($e['user'],'UTF-8','auto');
                //$e['user'] = utf8_decode($e['user']);
//                $e['user'] = utf8_encode($e['user']);
//                echo '<br/>'.mb_detect_order($e['user']);
                //$e['user'] = iconv('UCS-2LE','UTF-8',substr(base64_decode($e['user']),0,-1));
                //$e['user'] = html_entity_decode($e['user'], ENT_COMPAT | ENT_HTML401, 'UTF-8');
//                $e3[] = $e;
//        if ( $e['sum'] < 0 && ( $e['type'] == 'CASH' || $e['type'] == 'CARD' ) )
//            $sum2 += $e['sum'];
            }
//    \f\pa($e3);

            if (isset($_REQUEST['show']))
                echo '</table>';

            if (isset($_REQUEST['show'])) {
                echo '<p>'
                . 'Сумма плюс : ' . (int) $sum
                . '<br/>'
                . 'Сумма минус : ' . (int) $sum2
                . '<br/>'
                . 'Итого : ' . (int) ( $sum + $sum2 )
                . '</p>';
                exit;
            } else {
//                die(\f\end2('получили данные по обороту точки', true, array(
//                    'oborot' => (int) ( $sum + $sum2 ),
//                    'plus' => (int) $sum,
//                    'minus' => (int) $sum2
//                )));
                return \f\end3('получили данные по обороту точки', true, array(
                    'oborot' => (int) ( $sum + $sum2 ),
                    'plus' => (int) $sum,
                    'minus' => (int) $sum2
                ));
            }
        }
    }

    /**
     * получаем цифру оборота за месяц
     * @param type $db
     * @param type $sp
     * @param type $date
     * любая дата в этом месяце
     * @return boolean
     * @throws \Exception
     */
    public static function getOborotMonth($db, int $sp, $date, $mod_oborot = 'sale_point_oborot') {

        $date_start = date('Y-m-01', strtotime($date));

        if (!empty(self::$cash[$sp][$date_start]))
            return self::$cash[$sp][$date_start];

        $date_finish = date('Y-m-d', strtotime($date_start . ' +1 month -1 day'));

        if (1 == 1) {

            \Nyos\mod\items::$join_where = ' INNER JOIN `mitems-dops` md ON '
                    . ' `md`.`id_item` = mi.id '
                    . 'AND `md`.`name` = \'date\' '
                    . 'AND `md`.`value_date` >= \'' . $date_start . '\'  '
                    . 'AND `md`.`value_date` <= \'' . $date_finish . '\'  '
                    . ' INNER JOIN `mitems-dops` md2 ON '
                    . ' `md2`.`id_item` = mi.id '
                    . 'AND `md2`.`name` = \'sale_point\' '
                    . 'AND `md2`.`value` = \'' . $sp . '\' ';
            $oborots = \Nyos\mod\items::getItemsSimple3($db, $mod_oborot);
//    echo '<pre>';
//    \f\pa($oborots,2);
//    echo '</pre>';
            // $re = ['summa' => 0];
            self::$cash[$sp][$date_start] = 0;

            foreach ($oborots as $k => $v) {
                if (
                        isset($v['sale_point']) &&
                        $v['sale_point'] == $sp &&
                        isset($v['date']) &&
                        $v['date'] >= $date_start &&
                        $v['date'] <= $date_finish
                ) {

                    // $re[$v['dop']['date']] = $v['dop'];
                    // $re[$v['dop']['date']] = $v['dop']['oborot_server'];
                    // $re[$v['dop']['date']]['id'] = $v['id'];

                    self::$cash[$sp][$date_start] += $v['oborot_server'];
                } else {
                    echo '<br/>' . __LINE__;
                }
            }
        } else {

            $oborots = \Nyos\mod\items::getItemsSimple($db, $mod_oborot);
//    echo '<pre>';
//    \f\pa($oborots,2);
//    echo '</pre>';
            // $re = ['summa' => 0];
            self::$cash[$sp][$date_start] = 0;

            foreach ($oborots['data'] as $k => $v) {
                if (
                        isset($v['dop']['sale_point']) &&
                        $v['dop']['sale_point'] == $sp &&
                        isset($v['dop']['date']) &&
                        $v['dop']['date'] >= $date_start &&
                        $v['dop']['date'] <= $date_finish
                ) {

                    // $re[$v['dop']['date']] = $v['dop'];
                    // $re[$v['dop']['date']] = $v['dop']['oborot_server'];
                    // $re[$v['dop']['date']]['id'] = $v['id'];

                    self::$cash[$sp][$date_start] += $v['dop']['oborot_server'];
                }
            }

//    foreach ($oborots['data'] as $k => $v) {
//        if (isset($v['dop'])) {
//            
//        }
//    }
        }


        return self::$cash[$sp][$date_start];








        return \f\end3('получили данные по обороту точки', true, array(
            'oborot' => (int) ( $sum + $sum2 ),
            'plus' => (int) $sum,
            'minus' => (int) $sum2
        ));
    }

    /**
     * получаем массив точка продаж > дата в которых нет оборотов
     * @param класс $db
     * @param цифра $now_days
     * сколько дней назад проверяем
     * @return массив
     */
    public static function getNoData($db, $now_days = 40) {

        // $now_days = ( $_REQUEST['days'] ?? 4 );

        $dt = date('Y-m-d', $_SERVER['REQUEST_TIME'] - 3600 * 24 * $now_days);

        $return = [
            'date_Start' => $dt,
            'nodata' => []
        ];


        \Nyos\mod\items::$join_where = ' INNER JOIN `mitems-dops` mid '
                . ' ON mid.id_item = mi.id '
                . ' AND mid.name = \'date\' '
                . ' AND mid.value_date >= :ds ';
//             . ' AND mid.value_date <= :df '
        \Nyos\mod\items::$var_ar_for_1sql[':ds'] = $dt;


//            . ' INNER JOIN `mitems-dops` mid2 '
//            . ' ON mid2.id_item = mi.id '
//            . ' AND mid2.name = \'sale_point\' '
//            . ' AND mid2.value = :sp '
//    \Nyos\mod\items::$var_ar_for_1sql[':sp'] = $sp;
        // \Nyos\mod\items::$show_sql = true;
        $ob = \Nyos\mod\items::get($db, \Nyos\mod\JobDesc::$mod_oborots);

        foreach ($ob as $k => $v) {
            if (!empty($v['sale_point']) && !empty($v['date'])) {

                if (!empty($v['oborot_server']))
                    $return['oborots_local'][$v['sale_point']][$v['date']]['server'] = ( $v['oborot_server'] ?? '' );

                if (!empty($v['oborot_hand']))
                    $return['oborots_local'][$v['sale_point']][$v['date']]['hand'] = ( $v['oborot_hand'] ?? '' );
            }
        }
        // \f\pa($now_oborot, 2, '', 'текущиий оборот за прошедшие даты по точкам');

        $sps = \Nyos\mod\items::get($db, \Nyos\mod\JobDesc::$mod_sale_point);

        foreach ($sps as $k => $sp) {

            // echo '<Br/><br/>начало обработки точки '.$sp['id'].' '.$sp['head'];

            for ($i = 1; $i <= $now_days; $i++) {

                $now_date = date('Y-m-d', $_SERVER['REQUEST_TIME'] - $i * 24 * 3600);

                if (isset($return['oborots_local'][$sp['id']])) {

                    if (isset($return['oborots_local'][$sp['id']][$now_date])) {
                        // echo '<br/>есть дата ' . $now_date;
                    } else {

                        $return['nodata'][$sp['id']][$now_date] = null;
                        // echo '<br/>нет даты ' . $now_date;
                    }
                } else {
                    // echo '<br/>нет точки '.$sp['id'].' '.$sp['head'];
                    break;
                }

                // \f\pa($v);
            }
        }

        return \f\end3('точка дата данных которых нет', true, $return);
    }

    /**
     * получаем цифру оборота за день
     * @param type $db
     * @param type $sp
     * @param type $date
     * @return boolean
     * @throws \Exception
     */
    public static function getDayOborot($db, $sp, $date) {

        if ($date >= date('Y-m-d', $_SERVER['REQUEST_TIME']))
            return false;

//        // $oborots = \Nyos\mod\items::getItemsSimple($db, \Nyos\mod\JobDesc::$mod_oborots);
//        // $oborots = \Nyos\mod\items::getItemsSimple3($db, \Nyos\mod\JobDesc::$mod_oborots);
//        \Nyos\mod\items::$join_where = 
//                ' INNER JOIN `mitems-dops` mid ON mid.id_item = mi.id '
//                . ' AND mid.name = \'date\' AND mid.value_date = \'' . $date . '\' ' 
//                . ' INNER JOIN `mitems-dops` mid2 ON mid2.id_item = mi.id '
//                . ' AND mid2.name = \'sale_point\' AND mid2.value = \'' . $sp . '\' ' 
//                ;
//        $oborots = \Nyos\mod\items::get($db, \Nyos\mod\JobDesc::$mod_oborots);
//        \f\pa($oborots,'','','oborots');
//
//        foreach ($oborots as $k => $v) {
//            if (isset($v['sale_point']) && $v['sale_point'] == $sp && isset($v['date']) && $v['date'] == $date && isset($v['oborot_server'])) {
//                return $v['oborot_server'];
//            }
//        }

        \Nyos\mod\items::$join_where = ' INNER JOIN `mitems-dops` mid ON mid.id_item = mi.id '
                . ' AND mid.name = \'date\' AND mid.value_date = \'' . $date . '\' '
                . ' INNER JOIN `mitems-dops` mid2 ON mid2.id_item = mi.id '
                . ' AND mid2.name = \'sale_point\' AND mid2.value = \'' . $sp . '\' '
        ;
        $oborots = \Nyos\mod\items::get($db, \Nyos\mod\JobDesc::$mod_oborots);
        // \f\pa($oborots, '', '', 'oborots');

        if (!empty($oborots))
            foreach ($oborots as $k => $v) {
            
                if ( !empty($v['oborot_hand']) && $v['oborot_hand'] > 0 )
                    return $v['oborot_hand'];

                return $v['oborot_server'] ?? 0;
            }

        /**
         * если нет оборота в данных локальной БД то тащим с удалённой
         */
        return false;

        $sps = \Nyos\mod\items::getItemsSimple($db, \Nyos\mod\JobDesc::$mod_sale_point);

        if (isset($sps['data'][$sp]) && !empty($sps['data'][$sp]['dop']['id_tech_for_oborot'])) {

// получаем данные для конекта к удалённой бд

            foreach (\Nyos\Nyos::$menu as $k => $v) {
                if ($v['type'] == 'iiko_checks' && $v['version'] == 1) {

                    \Nyos\mod\IikoOborot::$db_type = $v['db_type'];
                    \Nyos\mod\IikoOborot::$db_host = $v['db_host'];
                    \Nyos\mod\IikoOborot::$db_port = $v['db_port'];
                    \Nyos\mod\IikoOborot::$db_base = $v['db_base'];
                    \Nyos\mod\IikoOborot::$db_login = $v['db_login'];
                    \Nyos\mod\IikoOborot::$db_pass = $v['db_pass'];

                    $e_cfg = true;

                    break;
                }
            }


            if (!isset($e_cfg)) {
                throw new \Exception('не найдены данные для коннекта', 202);
            }

//            echo __LINE__ . ' ---------- ';
//            \f\pa($sps['data'][$sp]);
            // $o = self::connectDb();
            $oborot = self::loadOborotFromServer($sps['data'][$sp]['dop']['id_tech_for_oborot'], $date);
            // \f\pa($oborot);
            // \f\pa($oborot['data']['oborot']);
            if (isset($oborot['data']['oborot']) && is_numeric($oborot['data']['oborot'])) {

                \Nyos\mod\items::addNewSimple($db, \Nyos\mod\JobDesc::$mod_oborots, [
                    'sale_point' => $sp,
                    'date' => $date,
                    'oborot_server' => $oborot['data']['oborot']
                ]);

                return $oborot['data']['oborot'];
            } else {
                return false;
            }
        }
    }

    public static function loadFromServerSaveItems($db, $sp, $date, $mod_sp = 'sale_point', $mod_sp_oborot = 'sale_point_oborot') {

        $date = date('Y-m-d', strtotime($_REQUEST['date']));

        \Nyos\mod\items::$where2 = ' AND `id` = \'' . (int) $sp . '\' ';
        \Nyos\mod\items::$limit1 = true;
        \Nyos\mod\items::$where2dop = ' AND `name` = \'id_tech_for_oborot\' ';

        $sp1 = \Nyos\mod\items::get($db, $mod_sp);
        // \f\pa($sp1);
        // $sp_id = $get_sp_d ?? $time_sp_key ?? $_REQUEST['key_iiko_from_sp'] ?? $_REQUEST['sp_key_iiko'] ?? false;
        // \f\pa($sp_id);
        // echo '<br/>'.__FILE__.' '.__LINE__;

        if (empty($sp1['id_tech_for_oborot']))
            throw new \Exception('У точки продаж не определён id для подгрузки оборотов', 211);

        \Nyos\mod\IikoOborot::$show_html = false;
        $ret = \Nyos\mod\IikoOborot::loadOborotFromServer($sp1['id_tech_for_oborot'], $date);

        // \f\pa($ret, '', '', 'oborot from server');
        // echo '<br/>' . __FILE__ . ' ' . __LINE__;

        \Nyos\mod\items::addNewSimple($db, $mod_sp_oborot, [
            'date' => $date,
            'sale_point' => $sp,
            'oborot_server' => $ret['data']['oborot']
                ]
        );

        return $ret['data']['oborot'];
    }

    public static function loadOborotFromServer_old2001071514($sp_key, $date) {

        if (empty(self::$db_connect))
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

        if (self::$show_html === true)
            echo '<pre>' . $sql . '</pre>';

        $ff = self::$db_connect->prepare($sql);

        $ff->execute();

        if (self::$show_html === true)
            echo '<table cellpadding=10 border=1 >'; // <tr><td>1</td><td>2</td></tr>';

        $sum = 0;

        $n = 1;

        while ($e = $ff->fetch()) {

            if (self::$show_html === true) {
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

            if (self::$show_html === true)
                echo '<tr>';

            foreach ($e as $k => $v) {

                if (self::$show_html === true)
                    echo '<td>' . iconv('windows-1251', 'utf-8', $v) . '</td>';

                if ($k == 'sumCard' || $k == 'sumCash')
                    $sum += $v;
            }

            if (self::$show_html === true)
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

        if (self::$show_html === true) {

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

        if (self::$show_html === true)
            echo '<pre>' . $sql . '</pre>';

        $ff = self::$db_connect->prepare($sql);
        $ff->execute();

        if (self::$show_html === true)
            echo '<table cellpadding=10 border=1 >'; // <tr><td>1</td><td>2</td></tr>';

        $sum2 = 0;

        $n = 1;

        while ($e = $ff->fetch()) {

            if (self::$show_html === true) {
                if ($n == 1) {
                    echo '<tr>';

                    foreach ($e as $k => $v) {
                        echo '<td>' . $k . '</td>';
                    }

                    echo '</tr>';
                }
            }
            $n++;

            if (self::$show_html === true) {

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

                    if (self::$show_html === true)
                        \f\pa($e);

                    $sum2 += $e['sum'];
                }
            }
        }

        if (self::$show_html === true)
            echo '</table>';

        if ($sum == 0) {
            throw new \Exception('не получилось получить сумму оборота за сутки');
        }

        return \f\end3('получили данные по обороту точки', true, array(
            'oborot' => (int) ( $sum + $sum2 ),
            'plus' => (int) $sum,
            'minus' => (int) $sum2
        ));
    }

    public static function loadOborotFromServer($sp_key, $date) {

        if (empty(self::$db_connect))
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

        if (self::$show_html === true)
            echo '<pre>' . $sql . '</pre>';

        $ff = self::$db_connect->prepare($sql);

        $ff->execute();

        if (self::$show_html === true)
            echo '<table cellpadding=10 border=1 >'; // <tr><td>1</td><td>2</td></tr>';

        $sum = 0;

        $n = 1;

        while ($e = $ff->fetch()) {

            if (self::$show_html === true) {
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

            if (self::$show_html === true)
                echo '<tr>';

            if ($e['unmodifiable'] == 1) {
                $re['receiptsSum2'] += (int) $e['receiptsSum'];
            }

            foreach ($e as $k => $v) {

                if (self::$show_html === true)
                    echo '<td>' . iconv('windows-1251', 'utf-8', $v) . '</td>';

                if ($k == 'sumCard' || $k == 'sumCash')
                    $sum += $v;

                if ($k == 'receiptsSum')
                    $re['receiptsSum'] += $v;
            }

            if (self::$show_html === true)
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

        if (self::$show_html === true) {

            echo '</table>';
            echo '<p>Сумма ' . $sum . '</p>';
            \f\pa($ar2, 2, '', '$ar2');
        }


        if (empty($ar2['date']) || empty($ar2['session_number']))
            throw new \Exception('нет важных данных ' . __FILE__ . ' #' . __LINE__);

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

        if (self::$show_html === true) {
            // echo '<pre>' . $sql . '</pre>';
            \f\pa($sql);
        }

        $ff = self::$db_connect->prepare($sql);
        $ff->execute();

        if (self::$show_html === true)
            echo '<table cellpadding=10 border=1 >'; // <tr><td>1</td><td>2</td></tr>';

        $sum2 = 0;

        $n = 1;

        while ($e = $ff->fetch()) {

            if (self::$show_html === true) {
                if ($n == 1) {
                    echo '<tr>';

                    foreach ($e as $k => $v) {
                        echo '<td>' . $k . '</td>';
                    }

                    echo '</tr>';
                }
            }
            $n++;

            if (self::$show_html === true) {

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
//
//                    if (self::$show_html === true)
//                        \f\pa($e);
//
//                    $sum2 += $e['sum'];
                }
//                elseif (isset($e['type']) && trim($e['type']) == 'CRED' ) {
//
//                    if (self::$show_html === true)
//                        \f\pa($e);
//
//                    $sum2 += $e['sum'];
//                }
            }
        }

        if (self::$show_html === true)
            echo '</table>';

        if ($sum == 0) {
            throw new \Exception('не получилось получить сумму оборота за сутки');
        }


        $re['oborot'] = (int) ( $sum + $sum2 );
        $re['plus'] = (int) $sum;
        $re['minus'] = (int) $sum2;

        return \f\end3('получили данные по обороту точки', true, $re);
    }

}
