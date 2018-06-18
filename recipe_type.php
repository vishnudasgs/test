<?php

class ControllerCatalogRecipeType extends Controller {

	private $error = array();



	public function index() {

		$this->load->language('catalog/recipe');



		$this->document->setTitle($this->language->get('heading_title'));



		$this->load->model('catalog/recipe_type');



		$this->getList();

	}



	public function add() {

		$this->load->language('catalog/recipe');



		$this->document->setTitle($this->language->get('heading_title'));



		$this->load->model('catalog/recipe_type');



		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {

			$this->model_catalog_recipe_type->addRecipeType($this->request->post);



			$this->session->data['success'] = $this->language->get('text_success');



			$url = '';



			if (isset($this->request->get['sort'])) {

				$url .= '&sort=' . $this->request->get['sort'];

			}



			if (isset($this->request->get['order'])) {

				$url .= '&order=' . $this->request->get['order'];

			}



			if (isset($this->request->get['page'])) {

				$url .= '&page=' . $this->request->get['page'];

			}



			$this->response->redirect($this->url->link('catalog/recipe_type', 'user_token=' . $this->session->data['user_token'] . $url, true));

		}



		$this->getForm();

	}



	public function edit() {

		$this->load->language('catalog/recipe');



		$this->document->setTitle($this->language->get('heading_title'));



		$this->load->model('catalog/recipe_type');



		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {

			$this->model_catalog_recipe_type->editRecipeType($this->request->get['recipe_type_id'], $this->request->post);



			$this->session->data['success'] = $this->language->get('text_success');



			$url = '';



			if (isset($this->request->get['sort'])) {

				$url .= '&sort=' . $this->request->get['sort'];

			}



			if (isset($this->request->get['order'])) {

				$url .= '&order=' . $this->request->get['order'];

			}



			if (isset($this->request->get['page'])) {

				$url .= '&page=' . $this->request->get['page'];

			}



			$this->response->redirect($this->url->link('catalog/recipe_type', 'user_token=' . $this->session->data['user_token'] . $url, true));

		}



		$this->getForm();

	}



	public function delete() {

		$this->load->language('catalog/recipe');



		$this->document->setTitle($this->language->get('heading_title'));



		$this->load->model('catalog/recipe_type');



		if (isset($this->request->post['selected']) && $this->validateDelete()) {

			foreach ($this->request->post['selected'] as $recipe_type_id) {

				$this->model_catalog_recipe_type->deleteManufacturer($recipe_type_id);

			}



			$this->session->data['success'] = $this->language->get('text_success');



			$url = '';



			if (isset($this->request->get['sort'])) {

				$url .= '&sort=' . $this->request->get['sort'];

			}



			if (isset($this->request->get['order'])) {

				$url .= '&order=' . $this->request->get['order'];

			}



			if (isset($this->request->get['page'])) {

				$url .= '&page=' . $this->request->get['page'];

			}



			$this->response->redirect($this->url->link('catalog/recipe_type', 'user_token=' . $this->session->data['user_token'] . $url, true));

		}



		$this->getList();

	}



	protected function getList() {

		if (isset($this->request->get['sort'])) {

			$sort = $this->request->get['sort'];

		} else {

			$sort = 'name';

		}



		if (isset($this->request->get['order'])) {

			$order = $this->request->get['order'];

		} else {

			$order = 'ASC';

		}



		if (isset($this->request->get['page'])) {

			$page = $this->request->get['page'];

		} else {

			$page = 1;

		}



		$url = '';



		if (isset($this->request->get['sort'])) {

			$url .= '&sort=' . $this->request->get['sort'];

		}



		if (isset($this->request->get['order'])) {

			$url .= '&order=' . $this->request->get['order'];

		}



		if (isset($this->request->get['page'])) {

			$url .= '&page=' . $this->request->get['page'];

		}



		$data['breadcrumbs'] = array();



		$data['breadcrumbs'][] = array(

			'text' => $this->language->get('text_home'),

			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)

		);



		$data['breadcrumbs'][] = array(

			'text' => $this->language->get('heading_title'),

			'href' => $this->url->link('catalog/recipe_type', 'user_token=' . $this->session->data['user_token'] . $url, true)

		);



		$data['add'] = $this->url->link('catalog/recipe_type/add', 'user_token=' . $this->session->data['user_token'] . $url, true);

		$data['delete'] = $this->url->link('catalog/recipe_type/delete', 'user_token=' . $this->session->data['user_token'] . $url, true);



		$data['recipe_types'] = array();



		$filter_data = array(

			'sort'  => $sort,

			'order' => $order,

			'start' => ($page - 1) * $this->config->get('config_limit_admin'),

			'limit' => $this->config->get('config_limit_admin')

		);



		$recipe_type_total = $this->model_catalog_recipe_type->getTotalRecipeTypes();



		$results = $this->model_catalog_recipe_type->getRecipeTypes($filter_data);



		foreach ($results as $result) {

			$data['recipe_types'][] = array(

				'recipe_type_id' => $result['recipe_type_id'],

				'name'            => $result['type_name'],

				'sort_order'      => $result['sort_order'],

				'edit'            => $this->url->link('catalog/recipe_type/edit', 'user_token=' . $this->session->data['user_token'] . '&recipe_type_id=' . $result['recipe_type_id'] . $url, true)

			);

		}



		if (isset($this->error['warning'])) {

			$data['error_warning'] = $this->error['warning'];

		} else {

			$data['error_warning'] = '';

		}



		if (isset($this->session->data['success'])) {

			$data['success'] = $this->session->data['success'];



			unset($this->session->data['success']);

		} else {

			$data['success'] = '';

		}



		if (isset($this->request->post['selected'])) {

			$data['selected'] = (array)$this->request->post['selected'];

		} else {

			$data['selected'] = array();

		}



		$url = '';



		if ($order == 'ASC') {

			$url .= '&order=DESC';

		} else {

			$url .= '&order=ASC';

		}



		if (isset($this->request->get['page'])) {

			$url .= '&page=' . $this->request->get['page'];

		}



		$data['sort_name'] = $this->url->link('catalog/recipe_type', 'user_token=' . $this->session->data['user_token'] . '&sort=name' . $url, true);

		$data['sort_sort_order'] = $this->url->link('catalog/recipe_type', 'user_token=' . $this->session->data['user_token'] . '&sort=sort_order' . $url, true);



		$url = '';



		if (isset($this->request->get['sort'])) {

			$url .= '&sort=' . $this->request->get['sort'];

		}



		if (isset($this->request->get['order'])) {

			$url .= '&order=' . $this->request->get['order'];

		}



		$pagination = new Pagination();

		$pagination->total = $recipe_type_total;

		$pagination->page = $page;

		$pagination->limit = $this->config->get('config_limit_admin');

		$pagination->url = $this->url->link('catalog/recipe_type', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}', true);



		$data['pagination'] = $pagination->render();



		$data['results'] = sprintf($this->language->get('text_pagination'), ($recipe_type_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($recipe_type_total - $this->config->get('config_limit_admin'))) ? $recipe_type_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $recipe_type_total, ceil($recipe_type_total / $this->config->get('config_limit_admin')));



		$data['sort'] = $sort;

		$data['order'] = $order;



		$data['header'] = $this->load->controller('common/header');

		$data['column_left'] = $this->load->controller('common/column_left');

		$data['footer'] = $this->load->controller('common/footer');



		$this->response->setOutput($this->load->view('catalog/recipe_type_list', $data));

	}



	protected function getForm() {

		$data['text_form'] = !isset($this->request->get['recipe_type_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');



		if (isset($this->error['warning'])) {

			$data['error_warning'] = $this->error['warning'];

		} else {

			$data['error_warning'] = '';

		}



		if (isset($this->error['name'])) {

			$data['error_name'] = $this->error['name'];

		} else {

			$data['error_name'] = '';

		}



		if (isset($this->error['keyword'])) {

			$data['error_keyword'] = $this->error['keyword'];

		} else {

			$data['error_keyword'] = '';

		}



		$url = '';



		if (isset($this->request->get['sort'])) {

			$url .= '&sort=' . $this->request->get['sort'];

		}



		if (isset($this->request->get['order'])) {

			$url .= '&order=' . $this->request->get['order'];

		}



		if (isset($this->request->get['page'])) {

			$url .= '&page=' . $this->request->get['page'];

		}



		$data['breadcrumbs'] = array();



		$data['breadcrumbs'][] = array(

			'text' => $this->language->get('text_home'),

			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)

		);



		$data['breadcrumbs'][] = array(

			'text' => $this->language->get('heading_title'),

			'href' => $this->url->link('catalog/recipe_type', 'user_token=' . $this->session->data['user_token'] . $url, true)

		);



		if (!isset($this->request->get['recipe_type_id'])) {

			$data['action'] = $this->url->link('catalog/recipe_type/add', 'user_token=' . $this->session->data['user_token'] . $url, true);

		} else {

			$data['action'] = $this->url->link('catalog/recipe_type/edit', 'user_token=' . $this->session->data['user_token'] . '&recipe_type_id=' . $this->request->get['recipe_type_id'] . $url, true);

		}



		$data['cancel'] = $this->url->link('catalog/recipe_type', 'user_token=' . $this->session->data['user_token'] . $url, true);



		if (isset($this->request->get['recipe_type_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {

			$recipe_type_info = $this->model_catalog_recipe_type->getRecipeType($this->request->get['recipe_type_id']);

		}



		$data['user_token'] = $this->session->data['user_token'];



		if (isset($this->request->post['name'])) {

			$data['name'] = $this->request->post['name'];

		} elseif (!empty($recipe_type_info)) {

			$data['name'] = $recipe_type_info['type_name'];

		} else {

			$data['name'] = '';

		}



		$this->load->model('setting/store');



		$data['stores'] = array();

		

		$data['stores'][] = array(

			'store_id' => 0,

			'name'     => $this->language->get('text_default')

		);

		

		$stores = $this->model_setting_store->getStores();



		foreach ($stores as $store) {

			$data['stores'][] = array(

				'store_id' => $store['store_id'],

				'name'     => $store['name']

			);

		}










		if (isset($this->request->post['sort_order'])) {

			$data['sort_order'] = $this->request->post['sort_order'];

		} elseif (!empty($recipe_type_info)) {

			$data['sort_order'] = $recipe_type_info['sort_order'];

		} else {

			$data['sort_order'] = '';

		}



		$this->load->model('localisation/language');



		$data['languages'] = $this->model_localisation_language->getLanguages();


		$data['header'] = $this->load->controller('common/header');

		$data['column_left'] = $this->load->controller('common/column_left');

		$data['footer'] = $this->load->controller('common/footer');



		$this->response->setOutput($this->load->view('catalog/recipe_type_form', $data));

	}



	protected function validateForm() {

		if (!$this->user->hasPermission('modify', 'catalog/recipe_type')) {

			$this->error['warning'] = $this->language->get('error_permission');

		}



		if ((utf8_strlen($this->request->post['name']) < 1) || (utf8_strlen($this->request->post['name']) > 64)) {

			$this->error['name'] = $this->language->get('error_name');

		}



		return !$this->error;

	}



	protected function validateDelete() {

		if (!$this->user->hasPermission('modify', 'catalog/recipe_type')) {

			$this->error['warning'] = $this->language->get('error_permission');

		}



		$this->load->model('catalog/product');



		foreach ($this->request->post['selected'] as $recipe_type_id) {

			$product_total = $this->model_catalog_product->getTotalProductsByManufacturerId($recipe_type_id);



			if ($product_total) {

				$this->error['warning'] = sprintf($this->language->get('error_product'), $product_total);

			}

		}



		return !$this->error;

	}



	public function autocomplete() {

		$json = array();



		if (isset($this->request->get['filter_name'])) {

			$this->load->model('catalog/recipe_type');



			$filter_data = array(

				'filter_name' => $this->request->get['filter_name'],

				'start'       => 0,

				'limit'       => 5

			);



			$results = $this->model_catalog_recipe_type->getRecipeTypes($filter_data);



			foreach ($results as $result) {

				$json[] = array(

					'recipe_type_id' => $result['recipe_type_id'],

					'name'            => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'))

				);

			}

		}



		$sort_order = array();



		foreach ($json as $key => $value) {

			$sort_order[$key] = $value['name'];

		}



		array_multisort($sort_order, SORT_ASC, $json);



		$this->response->addHeader('Content-Type: application/json');

		$this->response->setOutput(json_encode($json));

	}

}