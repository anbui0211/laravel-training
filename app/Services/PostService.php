<?php

namespace App\Services;

use App\Repositories\PostRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PostService
{

	public function __construct(private PostRepositoryInterface $postRepo)
	{
	}

	public function index()
	{
		$relation = ['user:id,name'];
		return $this->postRepo->findWithByRelation($relation);
	}

	public function store(array $data)
	{
		$file = $data['image'];
		$extension = $file->getClientOriginalExtension();
		$filename = time() . '.' . $extension;

		// Save the image file to local 
		$imageLocalPath = $file->storeAs('public/images/post', $filename);

		// Check if the bucket exists
		$awsBucket = config('filesystems.disks.s3.bucket');
		ensureBucketExists($awsBucket);

		// Upload to s3 and return image path  
		$imageS3Path = $file->storeAs('images/post', $filename, 's3');

		$postCreate = [
			'title' => $data['title'],
			'content' => $data['content'],
			'user_id' => $data['user_id'],
			'image_local' => $imageLocalPath,
			'image_s3' => $awsBucket . '/' . $imageS3Path,
		];
		$this->postRepo->insert($postCreate);
	}

	public function update(array $data)
	{
		$id = $data['id'];
		$arrUpdate = [
			'title' => $data['title'],
			'content' => $data['content'],
		];

		$this->postRepo->update($arrUpdate, $id);
	}

	public function destroy($id)
	{
		return $this->postRepo->delete($id);
	}
}
