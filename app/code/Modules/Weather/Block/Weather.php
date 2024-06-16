<?php
namespace Modules\Weather\Block;

use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\HTTP\Client\Curl;
use Psr\Log\LoggerInterface;

class Weather extends \Magento\Framework\View\Element\Template
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
}