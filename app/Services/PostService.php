<?php

namespace App\Services;

use App\Http\Requests\CreatePostRequest;
use App\Repositories\PostRepositoryInterface;

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
    $this->postRepo->insert($data);
  }

  public function update(array $data)
  {
    $id = $data['id'];
    $arrUpdate = [
      'content' => $data['title'],
      'title' => $data['title'],
    ];

    $this->postRepo->update($arrUpdate, $id);
  }

  public function destroy ($id) {
    return $this->postRepo->delete($id);
  }
}
