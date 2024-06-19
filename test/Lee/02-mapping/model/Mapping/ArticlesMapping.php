<?php

namespace model\Mapping;

use model\Abstract\AbstractMapping;
use DateTime;
use Exception;

class ArticlesMapping extends AbstractMapping
{

    protected ?int $article_id;
    protected ?string $article_title;
    protected ?string $article_slug;
    protected ?string $article_text;
    protected null|string|DateTime $article_date_create;
    protected null|string|DateTime $article_date_update;
    protected null|string|DateTime $article_date_publish;
    protected ?int $user_user_id;
 

    public function getArticleId(): ?int
    {
        return $this->article_id;
    }

    public function setArticleId(?int $article_id): void
    {
        if($article_id < 0) throw new Exception("ID NON VALIDE");
        $this->article_id = $article_id;
    }

    public function getArticleTitle(): ?string
    {
        return $this->article_title;
    }

    public function setArticleTitle(?string $article_title): void
    {
        $this->article_title = trim($article_title);
    }

    public function getArticleSlug(): ?string
    {
        return $this->article_slug;
    }

    public function setArticleSlug(?string $article_slug): void
    {
        $this->article_slug = $article_slug;
    }

    public function getArticleText(): ?string
    {
        return $this->article_text;
    }

    public function setArticleText(?string $article_text): void
    {
        $this->article_text = $article_text;
    }

    public function getArticleDateCreate(): null|string|DateTime
    {
        return $this->article_date_create;
    }

    public function setArticleDateCreate(null|string|DateTime $article_date_create): void
    {

        if(is_string($article_date_create)){
            try {

                $article_date = new DateTime($article_date_create);
                $this->article_date_create = $article_date->format("Y-m-d H:i:s");
            } catch (Exception $e) {

                $this->article_date_create = null;
            }

        }elseif (is_object($article_date_create)){
      
            $this->article_date_create = $article_date_create->format("Y-m-d H:i:s");
        }else{
            $this->article_date_create = null;
        }

    }

    public function getArticleDateUpdate(): null|string|DateTime
    {
        return $this->article_date_update;
    }

    public function setArticleDateUpdate(null|string|DateTime $article_date_update): void
    {

        if(is_string($article_date_update)){
            try {

                $article_date_update = new DateTime($article_date_update);
                $this->article_date_create = $article_date_update->format("Y-m-d H:i:s");
            } catch (Exception $e) {

                $this->article_date_update = null;
            }

        }elseif (is_object($article_date_update)){
      
            $this->article_date_update = $article_date_update->format("Y-m-d H:i:s");
        }else{
            $this->article_date_update = null;
        }

    }


    
public function getArticleDatePublish(): null|string|DateTime
{
    return $this->article_date_publish;
}

public function setArticleDatePublish(null|string|DateTime $article_date_publish): void
{

    if(is_string($article_date_publish)){
        try {

            $article_date_publish = new DateTime($article_date_publish);
            $this->article_date_create = $article_date_publish->format("Y-m-d H:i:s");
        } catch (Exception $e) {

            $this->article_date_publish = null;
        }

    }elseif (is_object($article_date_publish)){
  
        $this->article_date_publish = $article_date_publish->format("Y-m-d H:i:s");
    }else{
        $this->article_date_publish = null;
    }

}

public function getUserUserId(): ?int
{
    return $this->user_user_id;
}

public function setUserUserId(?int $user_user_id): void
{
    if($user_user_id < 0) throw new Exception("ID NON VALIDE");
    $this->user_user_id = $user_user_id;
}
}