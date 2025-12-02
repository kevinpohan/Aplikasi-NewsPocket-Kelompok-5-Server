<?php

namespace App\Models;

use CodeIgniter\Model;

class BookmarkModel extends Model
{
    protected $table = 'news_bookmarks';
    protected $primaryKey = 'id';
    protected $allowedFields = ['member_id', 'title', 'source_name', 'url_image', 'article_url', 'personal_notes'];
}
