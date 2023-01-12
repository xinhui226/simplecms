<?php

class Post{

    /**
     * list all posts
     */
    public static function getAllPosts()
    {
        return DB::connect()->select(
            'SELECT * FROM posts ORDER BY id DESC',
            [],
            true
        );
    }

    /**
     * get posts by post id provided
     */
    public static function getPostById($id)
    {
        return DB::connect()->select(
            'SELECT * FROM posts WHERE id=:id',
            [
                'id'=>$id
            ]
        );
    }

    /**
     * add new post
     */
    public static function add($title,$content,$userid)
    {
        return DB::connect()->insert(
            'INSERT INTO posts (title,content,user_id)
                VALUES(:title,:content,:id)',
            [
                'title'=>$title,
                'content'=>$content,
                'id'=>$userid
            ]
            );
    }

    /**
     * delete post
     */
    public static function delete($id)
    {
        return DB::connect()->delete(
            'DELETE FROM posts WHERE id=:id',
            [
                'id'=>$id
            ]
            );
    }

    /**
     * update post
     */
    public static function update($id,$title,$content,$status)
    {
        return DB::connect()->update(
            'UPDATE posts SET title=:title,content=:content,status=:status WHERE id=:id',
            [
                'title' =>$title,
                'content' =>$content,
                'status' =>$status,
                'id' =>$id
            ]
            );
    }
}