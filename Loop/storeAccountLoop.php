<?php
namespace TheliaStore\Loop;

use Thelia\Core\HttpFoundation\Session\Session;
use Thelia\Core\Template\Element\ArraySearchLoopInterface;
use Thelia\Core\Template\Element\BaseLoop;
use Thelia\Core\Template\Element\LoopResult;
use Thelia\Core\Template\Element\LoopResultRow;
use Thelia\Core\Template\Loop\Argument\Argument;
use Thelia\Core\Template\Loop\Argument\ArgumentCollection;
use TheliaStore\TheliaStore;

use Thelia\Api\Client\Client;

class StoreAccountLoop extends BaseLoop implements ArraySearchLoopInterface
{
    protected function getArgDefinitions()
    {
        return new ArgumentCollection();
    }

    public function buildArray()
    {

        $session = new Session();
        $dataAccount = $session->get('storecustomer');

        if ($dataAccount && is_array($dataAccount)) {

            $api = TheliaStore::getApi();

            list($status, $data) = $api->doGet('customers', $dataAccount['ID']);

            var_dump($data);

            if ($status == 200) {
                return $data;
            }

            return array();
        }
        return array();
    }

    public function parseResults(LoopResult $loopResult)
    {
        foreach ($loopResult->getResultDataCollection() as $entry) {

            $row = new LoopResultRow();

            $row->set("ID", $entry['ID'])
                ->set("REF", $entry['REF'])
                ->set("TITLE", $entry['TITLE'])
                ->set("FIRSTNAME", $entry['FIRSTNAME'])
                ->set("LASTNAME", $entry['LASTNAME'])
                ->set("EMAIL", $entry['EMAIL'])
                ->set("ADDRESS1", $entry['ADDRESS1'])
                ->set("CITY", $entry['CITY'])
                ->set("ZIPCODE", $entry['ZIPCODE'])
                ->set("COUNTRY", $entry['COUNTRY'])
                ->set("EMAIL", $entry['EMAIL'])
                ->set("PASSWORD", $entry['PASSWORD'])
                ->set("DISCOUNT", $entry['DISCOUNT'])
                ->set("NEWSLETTER", $entry['NEWSLETTER'])
                ->set("CREATE_DATE", $entry['CREATE_DATE']['date'])
                ->set("UPDATE_DATE", $entry['UPDATE_DATE']['date'])
                ->set("LOOP_COUNT", $entry['LOOP_COUNT'])
                ->set("LOOP_TOTAL", $entry['LOOP_TOTAL']);
            $loopResult->addRow($row);
        }

        return $loopResult;
    }
}