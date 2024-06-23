<?php

namespace model\Mapping;

use model\Abstract\AbstractMapping;
use model\Mapping\userMapping;

class CategoryMapping extends AbstractMapping
{
    
    protected ?int $category_id;
    protected ?string $category_name;
    protected ?string $category_slug;
    protected ?string $category_description;
    protected ?int $category_parent;

    protected array $user = [];


    public function getCategoryId(): ?int
    {
        return $this->category_id;
    }

    public function setCategoryId(?int $category_id): void
    {
        if($category_id > 0){
            $this->category_id = $category_id;
        }
    }

    public function getCategoryName(): ?string
    {
        return $this->category_name;
    }

    public function setCategoryName(?string $category_name): void
    {
        $name = htmlspecialchars(trim(strip_tags($category_name)),ENT_QUOTES);
        $this->category_name = $name;
    }

    public function getCategorySlug(): ?string
    {
        return $this->category_slug;
    }

    public function setCategorySlug(?string $category_slug): void
    {
        $slug = htmlspecialchars(trim(strip_tags($category_slug)),ENT_QUOTES);
        $this->category_slug = $slug;
    }

    public function getCategoryDescription(): ?string
    {
        return $this->category_description;
    }

    public function setCategoryDescription(?string $category_description): void
    {
        $this->category_description = htmlspecialchars(trim(strip_tags($category_description)), ENT_QUOTES);
    }

    public function getCategoryParent(): ?int
    {
        return $this->category_parent;
    }

    public function setCategoryParent(?int $category_parent): void
    {
        if($category_parent >= 0){
            $this->category_parent = $category_parent;
        }    
    }
}