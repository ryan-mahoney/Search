<?php
/**
 * virtuecenter\search
 *
 * Copyright (c)2013 Ryan Mahoney, https://github.com/virtuecenter <ryan@virtuecenter.com>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
namespace Search;

class Search {
	private $searchGateway;
	private $root;

	public function __construct ($searchGateway, $root) {
		$this->searchGateway = $searchGateway;
		$this->root = trim(str_replace('/', '__', $root), '__');
	}

	public function index ($id, $body, $type=null, $index=false) {
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

	public function search ($query, $type=null, $index=false) {
		if ($index === false) {
			$index = $this->root;
		}
		$searchParams['index'] = $index;
		$searchParams['type'] = $type;
		if (is_string($query)) {
			$searchParams['body']['query']['query_string']['query'] = $query;
		} else {
			$searchParams['body']['query'] = $query;
		}
		return $this->searchGateway->search($searchParams);
	}

	public function delete ($id, $type, $index=false) {
		if ($index === false) {
			$index = $this->root;
		}
		$deleteParams = [];
		$deleteParams['index'] = $index;
		$deleteParams['type'] = $type;
		$deleteParams['id'] = $id;
		return $this->searchGateway->delete($deleteParams);
	}
}