<?php

use model\Abstract\AbstractMapping;
class CategoryMapping extends AbstractMapping
{
    protected ?int $category_id;
    protected ?string $category_name;
    protected ?string $category_slug;
    protected ?string $category_description;
    protected ?int $category_parent;

    /**
     * @return int|null
     */
    public function getCategoryId(): ?int
    {
        return $this->category_id;
    }

    /**
     * @param int|null $category_id
     */
    public function setCategoryId(?int $category_id): void
    {
        $this->category_id = $category_id;
    }

    /**
     * @return string|null
     */
    public function getCategoryName(): ?string
    {
        return $this->category_name;
    }

    /**
     * @param string|null $category_name
     */
    public function setCategoryName(?string $category_name): void
    {
        $this->category_name = $category_name;
    }

    /**
     * @return string|null
     */
    public function getCategorySlug(): ?string
    {
        return $this->category_slug;
    }

    /**
     * @param string|null $category_slug
     */
    public function setCategorySlug(?string $category_slug): void
    {
        $this->category_slug = $category_slug;
    }

    /**
     * @return string|null
     */
    public function getCategoryDescription(): ?string
    {
        return $this->category_description;
    }

    /**
     * @param string|null $category_description
     */
    public function setCategoryDescription(?string $category_description): void
    {
        $this->category_description = $category_description;
    }

    /**
     * @return int|null
     */
    public function getCategoryParent(): ?int
    {
        return $this->category_parent;
    }

    /**
     * @param int|null $category_parent
     */
    public function setCategoryParent(?int $category_parent): void
    {
        $this->category_parent = $category_parent;
    }


}