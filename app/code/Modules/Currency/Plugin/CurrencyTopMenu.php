<?php
namespace Modules\Currency\Plugin;

use Magento\Framework\Data\Tree\NodeFactory;

class CurrencyTopMenu
{
    protected $nodeFactory;

    public function __construct(
        NodeFactory $nodeFactory
    ) {
        $this->nodeFactory = $nodeFactory;
    }

    public function beforeGetHtml(
        \Magento\Theme\Block\Html\Topmenu $subject,
        $outermostClass = '',
        $childrenWrapClass = '',
        $limit = 0
    ) {

        $node = $this->nodeFactory->create(
            [
                'data' => $this->getNodeAsArray(),
                'idField' => 'id',
                'tree' => $subject->getMenu()->getTree()
            ]
        );
        $subject->getMenu()->addChild($node);
    }

    protected function getNodeAsArray()
    {
        $root = (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
        $parsedUrl = parse_url($root);
        $root = $parsedUrl['scheme'] . '://' . $parsedUrl['host'];
        $url = $root . '/currency';
        return [
            'name' => __('Tỉ giá'),
            'id' => 'ti-gia-tien-te',
            'url' => "http://electronicworld.site/currency",
            'has_active' => false,
            'is_active' => false
        ];
    }

}