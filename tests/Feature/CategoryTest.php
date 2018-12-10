<?php

namespace Tests\Feature;

use App\User;
use App\Category;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;
    
    public function setUser()
    {
        $password = "dedihartono";

        $user = factory(User::class)->create([
            'name'     => 'Dedi Hartono',
            'email'    => 'dedihartono1993@gmail.com',
            'username' => 'dedihartono',
            'password' => \Hash::make($password),
            'roles'    => json_encode(["ADMIN"]),
        ]);
        
        return $user;
    }

    public function setCategoriesData()
    {
        $category = factory(Category::class)->create();

        return $category;
    }

    public function testViewAllCategoriesWithInCorrectUser()
    {
        $response = $this->get('/categories');

        $response->assertStatus(403);
    }
    
    public function testViewAllCategories()
    {
        $user = $this->setUser();

        $this->actingAs($user);

        $response = $this->get('/categories');

        $response->assertStatus(200);
    }

    public function testViewCategoryWithFiltering()
    {
        $user = $this->setUser();
        
        $this->actingAs($user);

        $category = $this->setCategoriesData();
        $name     = $category->name;        

        $response = $this->get('/categories?name='.$name);

        $response->assertStatus(200);
    }

    public function testCreateCategory()
    {
        $user = $this->setUser();
        
        $this->actingAs($user);

        $response = $this->get('/categories/create');

        $response->assertStatus(200);

        $response->assertSuccessful();
        
        $response->assertViewIs('categories.create');
    }

    public function testShowCategory()
    {
        $user = $this->setUser();
        
        $this->actingAs($user);
        
        $category = $this->setCategoriesData();
        
        $id       = $category->id;      

        $response = $this->get('/categories/'.$id);

        $response->assertStatus(200);

        $response->assertSuccessful();
        
        $response->assertViewIs('categories.show');
    }
    
    public function testEditCategory()
    {
        $user = $this->setUser();
        
        $this->actingAs($user);

        $category = $this->setCategoriesData();
        
        $id       = $category->id;      

        $response = $this->get('/categories/'.$id.'/edit');

        $response->assertStatus(200);

        $response->assertSuccessful();
        
        $response->assertViewIs('categories.edit');
    }

    public function testTrashCategory()
    {
        $user = $this->setUser();
        
        $this->actingAs($user);

        $response = $this->get('/categories/trash');

        $response->assertStatus(200);

        $response->assertSuccessful();
        
        $response->assertViewIs('categories.trash');
    }

    public function testDeletePermanenCategory()
    {
        $user = $this->setUser();
        
        $this->actingAs($user);

        $category = factory(Category::class)->create([
            'deleted_at'=> date('Y-m-d H:i:s'),
        ]);
        
        $categoryTrash       = Category::withTrashed()->first();
        $id = $categoryTrash->id;      

        $response = $this->call('DELETE','/categories/'.$id.'/delete-permanent', ['_token' => csrf_token()]);

        $response->assertStatus(302);
    }

}
