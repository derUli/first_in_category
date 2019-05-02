<?php

class FirstInCategoryTest extends \PHPUnit\Framework\TestCase {

    public function setUp() {
        Database::query("delete from `{prefix}categories` where name = 'my_category'", true);
        Database::query("delete from `{prefix}content` where slug = 'my_title' or slug = 'my_list' or slug = 'slug'", true);
    }

    public function tearDown() {
        $this->setUp();
    }

    private function getFirstUserId() {
        $manager = new UserManager();
        $users = $manager->getAllUsers();
        $first = $users[0];
        return $first->getId();
    }

    private function getFirstGroupId() {
        $groups = Group::getAll();
        $first = $groups[0];
        return $first->getId();
    }

    public function testGetFirstPageInCategory() {

        $category = new Category ();
        $category->setName("my_category");
        $category->save();
        $originalPage1 = new Page ();
        $originalPage1->menu = "none";
        $originalPage1->access = "all";
        $originalPage1->language = "de";
        $originalPage1->title = "my_page";
        $originalPage1->slug = "my_title";
        $originalPage1->category_id = $category->getID();

        $originalPage1->author_id = $this->getFirstUserId();
        $originalPage1->group_id = $this->getFirstGroupId();

        $originalPage1->save();

        $originalPage2 = new Page ();
        $originalPage2->title = "my_page";
        $originalPage2->slug = "my_title";
        $originalPage2->menu = "none";
        $originalPage2->access = "all";
        $originalPage2->language = "en";
        $originalPage2->category_id = $category->getID();

        $originalPage2->author_id = $this->getFirstUserId();
        $originalPage2->group_id = $this->getFirstGroupId();

        $originalPage2->save();
        $firstInCategory = ModuleHelper::getMainController("first_in_category");
        $page = $firstInCategory->getFirstPageInCategory($category->getID(), "de");
        $this->assertEquals($originalPage1->getID(), $page->id);
        $this->assertEquals($originalPage1->title, "my_page");
        $page = $firstInCategory->getFirstPageInCategory($category->getID(), "en");
        $this->assertEquals($originalPage2->getID(), $page->id);
        $this->assertEquals($originalPage1->title, "my_page");
    }

    public function testGetFirstListWithCategory() {
        $category = new Category ();
        $category->setName("my_category");
        $category->save();
        $originalPage1 = new Page ();
        $originalPage1->menu = "none";
        $originalPage1->access = "all";
        $originalPage1->language = "de";
        $originalPage1->title = "my_page";
        $originalPage1->slug = "my_title";
        $originalPage1->category_id = $category->getID();

        $originalPage1->author_id = $this->getFirstUserId();
        $originalPage1->group_id = $this->getFirstGroupId();

        $originalPage1->save();

        $originalPage2 = new Page ();
        $originalPage2->title = "my_page";
        $originalPage2->slug = "my_title";
        $originalPage2->menu = "none";
        $originalPage2->access = "all";
        $originalPage2->language = "en";
        $originalPage2->category_id = $category->getID();

        $originalPage2->author_id = $this->getFirstUserId();
        $originalPage2->group_id = $this->getFirstGroupId();

        $originalPage2->save();
        $listPage = new Content_List();

        $listPage->title = "my_list";
        $listPage->slug = "my_title";
        $listPage->menu = "my_list";
        $listPage->access = "all";
        $listPage->language = "de";

        $listPage->author_id = $this->getFirstUserId();
        $listPage->group_id = $this->getFirstGroupId();

        $listPage->save();
        $listPage->listData->content_id = $listPage->id;
        $listPage->listData->category_id = $category->getID();
        $listPage->listData->save();
        $firstInCategory = ModuleHelper::getMainController("first_in_category");
        $list = $firstInCategory->getFirstListWithCategory($category->getID(), "de");
        $this->assertEquals($listPage->id, $list->id);
    }

}
