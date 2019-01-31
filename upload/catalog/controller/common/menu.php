<?php
class ControllerCommonMenu extends Controller {
	public function index() {
		$this->load->language('common/menu');
		//footer

		$this->load->language('common/footer');

		$this->load->model('catalog/information');

		// Menu
		$this->load->model('catalog/category');

		$this->load->model('catalog/product');

		$data['categories'] = array();

		$data['text_catalog'] = $this->language->get('text_catalog');
		$data['contact'] = $this->url->link('information/contact');
		


		$data['informations'] = array();

		foreach ($this->model_catalog_information->getInformations() as $result) {
			// echo "<pre>";
			//     print_r($result); // or var_dump($data);
			//     echo "</pre>";
			if ($result['information_id'] === '8' || $result['information_id'] === '7' || $result['information_id'] === '4' || $result['information_id'] === '5') {
				$data['informations'][] = array(
					'title' => $result['title'],
					'href'  => $this->url->link('information/information', 'information_id=' . $result['information_id'])
				);
			}
		}

		$categories = $this->model_catalog_category->getCategories(0);

		foreach ($categories as $category) {
			if ($category['top']) {
				// Level 2
				$children_data = array();

				$children = $this->model_catalog_category->getCategories($category['category_id']);

				foreach ($children as $child) {
					$filter_data = array(
						'filter_category_id'  => $child['category_id'],
						'filter_sub_category' => true
					);

					$children_data[] = array(
						'name'  => $child['name'] . ($this->config->get('config_product_count') ? ' (' . $this->model_catalog_product->getTotalProducts($filter_data) . ')' : ''),
						'href'  => $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $child['category_id'])
					);
				}

				// Level 1
				$data['categories'][] = array(
					'name'     => $category['name'],
					'children' => $children_data,
					'column'   => $category['column'] ? $category['column'] : 1,
					'href'     => $this->url->link('product/category', 'path=' . $category['category_id'])
				);
			}
		}

		return $this->load->view('common/menu', $data);
	}
}
