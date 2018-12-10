<?php

namespace Tests\Unit;

use App\User;
use App\Category;
use App\Http\Controllers\CategoryController;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setCategoriesData()
    {
         $category = factory(Category::class)->create();
         
         return $category;
    }

    public function testEditCategoryDatas()
    {
        $category = $this->setCategoriesData();
        $id       = $category->id;

        $expect  = Category::first();
        $expect  = $expect->getAttributes();

        $tester = new CategoryController;
        $tester = $tester->edit($id);
        $actual = $tester->category->getAttributes();

        $this->assertEquals($expect,$actual);
    }

    public function testShowCategoryDatas()
    {
        $category = $this->setCategoriesData();
        $id       = $category->id;

        $expect  = Category::first();
        $expect  = $expect->getAttributes();

        $tester = new CategoryController;
        $tester = $tester->show($id);
        $actual = $tester->category->getAttributes();

        $this->assertEquals($expect,$actual);
    }

    public function testDestroyCategoryDatas()
    {
        $category = $this->setCategoriesData();
        $id       = $category->id;

        $tester = new CategoryController;
        $tester = $tester->destroy($id);

        $actual = $category->getAttributes();

        $categoryTrash = Category::withTrashed()->first();
        $expect = $categoryTrash->getAttributes();

        $this->assertEquals($expect['id'],$actual['id']);
        $this->assertEquals($expect['name'],$actual['name']);
        $this->assertEquals($expect['slug'],$actual['slug']);
        $this->assertEquals($expect['image'],$actual['image']);
    }

    public function testRestoreCategoryDatas()
    {
        $category = $this->setCategoriesData();
        $actual = $category->getAttributes();

        
        $categoryTrash = Category::withTrashed()->first();
        
        $id = $categoryTrash->id;

        $tester = new CategoryController;
        $tester = $tester->restore($id);
       
        $expect = Category::first()->getAttributes();

        $this->assertEquals($expect['id'],$actual['id']);
        $this->assertEquals($expect['name'],$actual['name']);
        $this->assertEquals($expect['slug'],$actual['slug']);
        $this->assertEquals($expect['image'],$actual['image']);
    }
}
