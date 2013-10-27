<?php
namespace Search;

class Search {
	private $searchGateway;
	private $root;

	public function __construct ($searchGateway, $root) {
		$this->searchGateway = $searchGateway;
		$this->root = $root;
	}

	public function index ($type='all', $id, $body, $index=false) {
		if ($index === false) {
			$index = $this->root;
		}
		$params = [];
		$params['body'] = $body;
		$params['index'] = $index;
		$params['type'] = $type;
		$params['id'] = (string)$id;
		return $this->searchGateway->index($params);
	}

	public function search () {

	}
}