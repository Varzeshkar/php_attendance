<?php

namespace Admin;
use database\Database;

require_once BASE_PATH . "/activities/Admin/Admin.php";

class Category extends Admin
{


    
    public function index()
    {
        $db = new Database();
        $categories = $db->select("SELECT * FROM `categories` order by `id` DESC");
        require_once BASE_PATH . "/template/admin/categories/index.php";
    }

    public function create()
    {
        $db = new Database();
        require_once BASE_PATH . "/template/admin/categories/create.php";

    }

    public function store($request)
    {
        $db = new Database( );
//        $db->insert("categories" , )
        $db->insert("categories" , array_keys($request) , $request);
        $this->redirect("/admin/category");
    }


    public function edit($id)
    {
        $db = new DataBase();
        $category = $db->select('SELECT * FROM categories WHERE id = ?;', [$id])->fetch();
        require_once(BASE_PATH . '/template/admin/categories/edit.php');
    }

    public function update($request, $id)
    {
        $db = new DataBase();
        $db->update('categories', $id, array_keys($request), $request);
        $this->redirect('admin/category');

    }

    public function delete($id)
    {
        $db = new DataBase();
        $db->delete('categories', $id);
        $this->redirect('admin/category');
    }
}

