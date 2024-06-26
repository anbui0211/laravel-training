<?php

namespace App\Services;


use App\Repositories\PostRepositoryInterface;

use function App\Helpers\randomData2;

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
    // $path = 'uploads/images/post/' ;
    // $file->move($path, $filename);

    // Save the image file to local 
    $imageLocalPath = $file->storeAs('uploads/images/post/', $filename);

    // Check if the bucket exists
    $awsBucket = config('filesystems.disks.s3');

    dd($awsBucket);
    dd(randomData2());
    ensureBucketExists($awsBucket);
    // Create s3 path 
    // $s3Path = 'kozo/images/post';
    // Save image to the S3 bucket
    $imageS3Path = $file->storeAs('kozo/images/post', $filename, 's3');



    $postCreate = [
      'title' => $data['title'],
      'content' => $data['content'],
      'user_id' => $data['user_id'],
      'image_local' => $imageLocalPath . $filename,
      'image_s3' => $imageS3Path . $filename,
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
