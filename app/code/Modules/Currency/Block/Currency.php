<?php
namespace Modules\Currency\Block;

use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\HTTP\Client\Curl;
use Psr\Log\LoggerInterface;

class Currency extends \Magento\Framework\View\Element\Template
{
    protected $curl;
    protected $logger;

    public function __construct(
        Context $context,
        Curl $curl,
        LoggerInterface $logger,
        array $data = []
    )
    {
        parent::__construct($context, $data);
        $this->curl = $curl;
        $this->logger = $logger;
    }

    /**
     * @return array
     */
    public function getCurrencyData(): array
    {
        $this->curl->get('https://portal.vietcombank.com.vn/Usercontrols/TVPortal.TyGia/pXML.aspx?b=68');
        $response = $this->curl->getBody();
        $xml = simplexml_load_string($response);
        $currencyData = [];
        foreach ($xml->Exrate as $item) {
            $currencyData[] = [
                'currency_code' => (string)$item['CurrencyCode'],
                'currency_name' => (string)$item['CurrencyName'],
                'buy' => (string)$item['Buy'],
                'sell' => (string)$item['Sell'],
                'transfer' => (string)$item['Transfer'],
            ];
        }
        return $currencyData;
    }
}