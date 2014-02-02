<?php
/**
 * Opine\Search
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
namespace Opine;

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
		//$searchParams['body']['highlight']['fields']['title' => []];
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
		$response = false;
		try {
			$response = $this->searchGateway->delete($deleteParams);
		} catch (\Exception $e) {}
		return $response;
	}

	public function searchPublic ($query) {
		//apply acl = public
		
		//apply type = published
	}

	public function indexToDefault ($id, $type, $title, $description=null, $image=null, $tags=[], $categories=[], $date=null, $dateCreated=null, $dateModified=null, $status='published', $featured='f', $acl=['public'], $urlManager=null, $urlPublic='', $index=false) {
		return $this->index(
			$id, [
				'title' => $title,
				'description' => $description,
				'image' => $image,
				'tags' => $tags,
				'categories' => $categories,
				'date' => $date,
				'created_date' => $dateCreated,
				'modified_date'	=> $dateModified,
				'status' => $status,
				'featured' => $featured,
				'acl' => $acl,
				'url' => $urlPublic,
				'url_manager' => $urlManager
			], 
			$type,
			$index
		);
	}

	public function indexCreateDefault ($index=false) {
		if ($index === false) {
			$index = $this->root;
		}
		$indexParams['index']  = $index;
		$indexParams['body']['settings']['number_of_shards'] = 5;
		$indexParams['body']['settings']['number_of_replicas'] = 1;
		$indexParams['body']['mappings']['my_type'] = [
    		'_source' => [
        		'enabled' => true
    		],
    		'properties' => [
        		'title' => [
            		'type' => 'string',
            		'store' => 'yes',
                	'index' => 'analyzed',
                	'boost' => 2,
                	'index_options' => 'offsets'
        		],
        		'description' => [
            		'type' => 'string',
            		'store' => 'yes',
                	'index' => 'analyzed'
        		],
        		'image' => [
            		'type' => 'string',
            		'store' => 'no',
                	'index' => 'no'
        		],
        		'tags' => [
					'type' => 'string', 
					'index_name' => 'tag',
					'store' => 'yes',
					'index' => 'analyzed',
					'boost' => 2
        		],
        		'categories' => [
					'type' => 'string', 
					'index_name' => 'category',
					'store' => 'yes',
					'index' => 'not_analyzed'
        		],
        		'date' => [
        			'type'  => 'date',
        			'format' => 'date_time',
        			'index' => 'analyzed',
        			'store' => 'no'
        		],
        		'created_date' => [
        			'type'  => 'date',
        			'format' => 'date_time',
        			'index' => 'analyzed',
        			'store' => 'no'
        		],
        		'modified_date' => [
        			'type'  => 'date',
        			'format' => 'date_time',
        			'index' => 'analyzed',
        			'store' => 'no'
        		],
        		'status' => [
        			'type' => 'string',
        			'index' => 'not_analyzed',
        			'store' => 'no'
        		],
        		'featured' => [
        			'type' => 'string',
        			'index' => 'not_analyzed',
        			'store' => 'no'
        		],
        		'acl' => [
					'type' => 'string', 
					'store' => 'no',
					'index' => 'not_analyzed'
        		],
        		'url' => [
        			'type' => 'string',
        			'index' => 'no',
        			'store' => 'no'
        		],
        		'url_manager' => [
        			'type' => 'string',
        			'index' => 'no',
        			'store' => 'no'
        		]
    		]
		];

		$this->searchGateway->indices()->create($indexParams);
	}
}