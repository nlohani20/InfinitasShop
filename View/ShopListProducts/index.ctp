<?php
/**
 * Add some documentation for this index form.
 *
 * @copyright Copyright (c) 2009 Carl Sutton (dogmatic69)
 *
 * @link		  http://infinitas-cms.org/Shop
 * @package	   Shop.View.index
 * @license	   http://infinitas-cms.org/mit-license The MIT License
 * @since 0.9b1
 *
 * @author dogmatic69
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 */

if (empty($shopListProducts)) {
	echo $this->Design->alert(__d('shop', 'You dont have anything in your cart yet'));
	return;
}

if (empty($shopList['ShopShipping'])) {
	echo $this->Design->alert(__d('shop', 'No shipping method selected'));
}

echo $this->Form->create(null, array('action' => 'mass'));
?>
<table class="listing">
	<?php
		echo $this->Infinitas->adminTableHeader(array(
			__d('shop', 'Image') => array(
				'style' => 'width: 100px;'
			),
			__d('shop', 'Product'),
			__d('shop', 'Quantity') => array(
				'style' => 'width: 100px;'
			),
			__d('shop', 'Each') => array(
				'style' => 'width: 100px;'
			),
			__d('shop', 'Sub Total') => array(
				'style' => 'width: 100px;'
			),
			__d('shop', 'Actions') => array(
				'width' => '50px'
			)
		), false);
		echo '<tbody>';
		foreach ($shopListProducts as $k => $shopListProduct) { ?>
			<tr>
				<td rowspan="<?php echo !empty($shopListProduct['ShopProductVariant']['ShopOptionVariant']) ? 2 : 1; ?>">
					<?php
					echo $this->Html->image($shopListProduct['ShopImage']['image_small']);
					?>
				</td>
				<td>
					<?php
						echo $this->Html->link($shopListProduct['ShopProduct']['name'], array(
							'controller' => 'shop_products',
							'action' => 'view',
							'slug' => $shopListProduct['ShopProduct']['slug'],
							'category' => $shopListProduct['ShopCategory'][0]['slug']
					));
					?>&nbsp;
				</td>
				<td>
					<?php
						$formField = 'ShopListProduct.' . $k;
						echo $this->Form->input($formField . '.id', array(
							'value' => $shopListProduct['ShopListProduct']['id']
						));
						echo $this->Form->input($formField . '.quantity', array(
							'value' => $shopListProduct['ShopListProduct']['quantity'],
							'type' => 'text',
							'div' => false,
							'label' => false
						));
					?>
				</td>
				<td><?php echo $this->Shop->price($shopListProduct['ShopProductVariant']['ShopProductVariantPrice'], false); ?></td>
				<td>
					<?php
						echo $this->Shop->subtotalPrice(
							$shopListProduct['ShopProductVariant']['ShopProductVariantPrice'],
							$shopListProduct['ShopListProduct']['quantity']
						);
					?>
				</td>
				<td>
					<?php
						echo $this->Html->link($this->Design->icon('trash'), array(
							'action' => 'delete',
							$shopListProduct['ShopListProduct']['id']
						), array('class' => 'btn btn-danger btn-small', 'escape' => false));
					?>&nbsp;
				</td>
			</tr><?php
			if (!empty($shopListProduct['ShopProductVariant']['ShopOptionVariant'])) {
				$options = array(
					$this->Html->tag('dt', __d('shop', 'Product code')),
					$this->Html->tag('dd', !empty($shopListProduct['ShopProduct']['product_code']) ? $shopListProduct['ShopProduct']['product_code'] : '-')
				);
				foreach ($shopListProduct['ShopProductVariant']['ShopOptionVariant'] as $productOption) {
					$help = $this->Html->link($this->Design->icon('question-sign'), $this->here . '#', array(
						'escape' => false,
						'data-title' => $productOption['ShopOption']['name'],
						'data-content' => strip_tags($productOption['ShopOption']['description']),
						'class' => 'help'
					));
					$options[] = $this->Html->tag('dt', $productOption['ShopOption']['name'] . $help);
					$options[] = $this->Html->tag('dd', $productOption['ShopOptionValue']['name']);
				}

				$size = '-';
				if (array_filter($shopListProduct['ShopProductVariant']['ShopProductVariantSize'])) {
					$size = $this->Shop->size($shopListProduct['ShopProductVariant']['ShopProductVariantSize'], true) . sprintf('(%s)', $this->Shop->sizeLabel());
				}
				echo $this->Html->tag('tr', implode('', array(
					$this->Html->tag('td',
						$this->Html->tag('dl', implode('', $options), array('class' => 'dl-horizontal')),
						array('colspan' => 2)
					),
					$this->Html->tag('td', $size, array(
						'colspan' => 100
					))
				)));
			}
		}
	?></tbody>
</table>
<?php
	echo $this->Html->tag('table', $this->Html->tag('thead', implode('', array(
		$this->Html->tag('tr', implode('', array(
			$this->Html->tag('td', ''),
			$this->Html->tag('td', __d('shop', 'Sub Total'), array('style' => 'width: 100px;')),
			$this->Html->tag('td', $this->Shop->cartPrice($shopListProducts), array('style' => 'width: 100px;'))
		))),
		$this->Html->tag('tr', implode('', array(
			$this->Html->tag('td', ''),
			$this->Html->tag('td', __d('shop', 'Shipping')),
			$this->Html->tag('td', $this->Shop->shipping($shopShippingMethod))
		))),
		$this->Html->tag('tr', implode('', array(
			$this->Html->tag('td', ''),
			$this->Html->tag('td', __d('shop', 'Total')),
			$this->Html->tag('td', ' ' . $this->Shop->cartPrice($shopListProducts, $shopShippingMethod))
		)))
	))), array('class' => 'shipping'));
	echo $this->Html->tag('div', implode('', array(
		$this->Shop->shippingSelect($shopList['ShopShippingMethod'], $shopShippingMethods),
		$this->Shop->paymentSelect($shopList['ShopPaymentMethod'], $shopPaymentMethods)
	)), array('class' => 'btn-group pull-left'));

	echo $this->Html->tag('div', implode('', array(
		$this->Form->button(__d('shop', 'Update'), array(
			'class' => 'btn',
			'name' => 'action',
			'value' => 'update'
		)),
		$this->Form->button(__d('shop', 'Checkout'), array(
			'class' => 'btn btn-info',
			'name' => 'action',
			'value' => 'checkout'
		))
	)), array('class' => 'btn-group pull-right'));
	echo $this->Form->end();