<?php
namespace Modules\Weather\Plugin;

use Magento\Framework\Data\Tree\NodeFactory;

class WeatherTopMenu
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
        return [
            'name' => __('Thời tiết'),
            'id' => 'thoi-tiet',
            'url' => "http://electronicworld.site/weather",
            'has_active' => false,
            'is_active' => false
        ];
    }

}