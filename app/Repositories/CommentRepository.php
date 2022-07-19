<?php 

namespace App\Repositories;

use App\Models\Comment;
use Illuminate\Support\Facades\DB;

class CommentRepository {

    public function create(array $attributes)
    {
        return DB::transaction(function () use ($attributes) {
            $created = Comment::query()->create([
                'title' => data_get($attributes, 'title'),
                'body' => data_get($attributes, 'body'),
                'user_id' => data_get($attributes, 'user_id'),
                'post_id' => data_get($attributes, 'post_id'),
            ]);
            return $created;
        });
    }

    public function update(Comment $comment, array $attributes)
    {
        return DB::transaction(function() use ($comment, $attributes) {
            $updated = $comment->update([
                'title' => data_get($attributes, 'title', $comment->title),
                'body' => data_get($attributes, 'body', $comment->body),
                'user_id' => data_get($attributes, 'user_id', $comment->user_id),
                'post_id' => data_get($attributes, 'post_id', $comment->post_id),
            ]);

            if(!$updated) {
                throw new \Exception('Failed to update comment');
            }

            return $comment;
        });
    }

    public function forceDelete(Comment $comment)
    {
        return DB::transaction(function () use ($comment) {
            $deleted = $comment->forceDelete();

            if(!$deleted) {
                throw new \Exception('Cannot delete the comment.');
            }
            return $deleted;
        });
    }
}


