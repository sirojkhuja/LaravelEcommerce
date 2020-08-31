<?php

namespace App\Repositories;
use App\Repositories\BaseRepository;
use App\Contracts\BrandContract;
use App\Models\Brand;

class BrandRepository extends BaseRepository implements BrandContract{

	public function __construct(Brand $model){
		parent::__construct($model);
		$this->model = $model;
	}

	public function listBrands(string $order = 'id', string $sort = 'desc', array $columns = ['*'])
	{
		return $this->all($columns, $order, $sort);
	}

	public function createBrand(array $params){
		try {
			$collection = collect($params);
			$logo = null;
			if($collection->has('logo') && ($params['logo'] instanceof UploadedFile )){
				$logo = $this->uploadOne($params['logo'], 'brands');
			}

			$merge = $collection->merge(compact('logo'));
			$brand = new Brand($merge->all());
			$brand->save();
			return $brand;

		} catch (QueryException $e) {
			throw new InvalidArgumentException($e->getMessage());
			
		}
	}
}
